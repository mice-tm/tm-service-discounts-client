<?php
namespace micetm\Clients\ServiceDiscounts\api;

use Buzz\Browser;
use Buzz\Client\FileGetContents;
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
        $this->browser = new Browser(new FileGetContents(array()));
    }

    /**
     * @param array $params
     * @return Discount|void
     */
    public function createNewDiscount($params = array())
    {
        if (empty($params)) {
            return;
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
            return new Discount(json_decode($response->getBody(), true));
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount creation error. " . $exception->getMessage(),
                $exception->getTrace()
            );
        }
    }

    /**
     * @param $code
     * @return Discount|void
     */
    public function viewDiscount($code)
    {
        if (empty($code)) {
            return;
        }

        try {
            $response = $this->browser->get(
                $this->serviceUrl . "/" . $code
            );
            if (200 !== $response->getStatusCode()) {
                throw new \Exception($response->getReasonPhrase());
            }
            return new Discount(json_decode($response->getBody(), true));
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount view request error ". $code,
                $exception->getTrace()
            );
        }
    }

    /**
     * @param string $code
     * @param array $products
     * @return Discount|void
     */
    public function applyDiscount($code, $products = array())
    {
        if (empty($products) || empty($code)) {
            return;
        }

        try {
            $response = $this->browser->post(
                $this->serviceUrl . "/" . $code . "/applicability",
                array(
                    'Authorization' => $this->accessToken,
                    'Content-Type' => 'application/json'
                ),
                json_encode(array(
                    'products' => $products
                ))
            );
            if (200 !== $response->getStatusCode()) {
                throw new \Exception($response->getReasonPhrase());
            }
            return new Discount(json_decode($response->getBody(), true));
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount applicability request error ". $code,
                $exception->getTrace()
            );
        }
    }

    /**
     * @param string $code
     * @return Discount|void
     */
    public function deleteDiscount($code)
    {
        if (empty($code)) {
            return;
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
            return new Discount(json_decode($response->getBody(), true));
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount delete request error ". $code,
                $exception->getTrace()
            );
        }
    }

    /**
     * @param $code
     * @param array $params
     * @return Discount|void
     */
    public function updateDiscount($code, $params = array())
    {
        if (empty($code) || empty($params)) {
            return;
        }

        try {
            $response = $this->browser->put(
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
            return new Discount(json_decode($response->getBody(), true));
        } catch (\Exception $exception) {
            $this->logger->warning(
                "Discount update request error ". $code . " " . $exception->getMessage(),
                $exception->getTrace()
            );
        }
    }
}
