<?php
namespace micetm\Clients\ServiceDiscounts\api;

use Buzz\Browser;
use micetm\Clients\ServiceDiscounts\exceptions\DiscountNotFound;
use micetm\Clients\ServiceDiscounts\exceptions\RequiredFieldsEmpty;
use micetm\Clients\ServiceDiscounts\exceptions\RuntimeException;
use micetm\Clients\ServiceDiscounts\models\Discount;
use Psr\Log\LoggerInterface;

class DiscountsClient implements DiscountsClientInterface
{
    /** @var string */
    protected $accessToken;

    /** @var string */
    protected $serviceUrl;

    /** @var Browser */
    protected $browser;

    /** @var LoggerInterface */
    protected $logger;

    /**
     * DiscountsClient constructor.
     * @param $accessToken
     * @param $serviceUrl
     * @param LoggerInterface $logger
     */
    public function __construct($accessToken, $serviceUrl, LoggerInterface $logger)
    {
        $this->accessToken = $accessToken;
        $this->serviceUrl = $serviceUrl . 'v2/discounts';
        $this->logger = $logger;
        $this->browser = new Browser();
    }

    /**
     * @inheritdoc
     */
    public function createNewDiscount($params = array())
    {
        if (empty($params)) {
            throw new RequiredFieldsEmpty(array('params'));
        }

        try {
            $response = $this->browser->post(
                $this->serviceUrl,
                array(
                    'Authorization' => $this->accessToken,
                    'Content-Type' => 'application/json'
                ),
                json_encode($params)
            );
            if (201 !== $response->getStatusCode()) {
                throw new \Exception($response->getReasonPhrase());
            }
            return new Discount(json_decode($response->getContent(), true));
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount creation error. " . $exception->getMessage(),
                $exception->getTrace()
            );
            throw new RuntimeException(
                "Discount creation error. " . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function viewDiscount($code)
    {
        if (empty($code)) {
            throw new RequiredFieldsEmpty(array('code'));
        }

        try {
            $response = $this->browser->get(
                $this->serviceUrl . "/" . $code
            );
            if (200 !== $response->getStatusCode()) {
                throw new \Exception($response->getReasonPhrase());
            }
            return new Discount(json_decode($response->getContent(), true));
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount view request error ". $code,
                $exception->getTrace()
            );
            throw new DiscountNotFound($code);
        }
    }

    /**
     * @inheritdoc
     */
    public function applyDiscount($code, $products = array(), $cart = array())
    {
        if (empty($products) || empty($code)) {
            throw new RequiredFieldsEmpty(array('products', 'code'));
        }

        try {
            $response = $this->browser->post(
                $this->serviceUrl . "/" . $code . "/applicability",
                array(
                    'Authorization' => $this->accessToken,
                    'Content-Type' => 'application/json'
                ),
                json_encode(array(
                    'items' => $products,
                    'cart' => $cart,
                ))
            );
            if (200 !== $response->getStatusCode()) {
                throw new \Exception($response->getReasonPhrase());
            }
            return new Discount(json_decode($response->getContent(), true));
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount applicability request error ". $code,
                $exception->getTrace()
            );

            throw new DiscountNotFound($code);
        }
    }

    /**
     * @inheritdoc
     */
    public function deleteDiscount($code)
    {
        if (empty($code)) {
            throw new RequiredFieldsEmpty(array('code'));
        }

        try {
            $response = $this->browser->delete(
                $this->serviceUrl . "/" . $code,
                array(
                    'Authorization' => $this->accessToken,
                )
            );
            if (204 !== $response->getStatusCode()) {
                throw new \Exception($response->getReasonPhrase());
            }
            return true;
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount delete request error ". $code,
                $exception->getTrace()
            );
            throw new RuntimeException(
                "Discount delete request error " . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function updateDiscount($code, $params = array())
    {
        if (empty($code) || empty($params)) {
            throw new RequiredFieldsEmpty(array('code', 'params'));
        }

        try {
            $response = $this->browser->patch(
                $this->serviceUrl . "/" . $code,
                array(
                    'Authorization' => $this->accessToken,
                    'Content-Type' => 'application/json'
                ),
                json_encode($params)
            );
            if (200 !== $response->getStatusCode()) {
                throw new \Exception($response->getReasonPhrase());
            }
            return new Discount(json_decode($response->getContent(), true));
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount update request error ". $code . " " . $exception->getMessage(),
                $exception->getTrace()
            );

            throw new RuntimeException(
                "Discount update request error " . $exception->getMessage(),
                $exception->getCode(),
                $exception
            );
        }
    }

    /**
     * @inheritdoc
     */
    public function findDiscounts($params = array())
    {
        try {
            $response = $this->browser->get(
                $this->serviceUrl . '?' . http_build_query($params),
                array(
                    'Authorization' => $this->accessToken,
                    'Content-Type' => 'application/json'
                )
            );
            if (200 !== $response->getStatusCode()) {
                throw new \Exception($response->getReasonPhrase());
            }
            $discountList = array();
            foreach (json_decode($response->getContent(), true) as $discount) {
                $discountList[] = new Discount($discount);
            }
            return $discountList;
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount find request error " . $exception->getMessage(),
                $exception->getTrace()
            );

            return array();
        }
    }
}
