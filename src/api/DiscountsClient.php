<?php
namespace micetm\Clients\ServiceDiscounts\api;

use Buzz\Browser;
use Buzz\Client\FileGetContents;
use micetm\Clients\ServiceDiscounts\models\Discount;
use Psr\Log\LoggerInterface;

class DiscountsClient
{
    /** @var string */
    protected $accessToken;

    /** @var string */
    protected $serviceUrl;

    /** @var Browser */
    protected $browser;

    /** @var LoggerInterface */
    protected $logger;

    public function __construct($accessToken, $serviceUrl, LoggerInterface $logger = null)
    {
        $this->accessToken = $accessToken;
        $this->serviceUrl = $serviceUrl . 'v2/discounts';
        $this->logger = $logger;
        $this->browser = new Browser(new FileGetContents([]));
    }

    public function createNewDiscount($params = [])
    {
        try {
            $response = $this->browser->post(
                $this->serviceUrl,
                [
                    'Authorization' => $this->accessToken,
                    'Content-Type' => 'application/json'
                ],
                json_encode($params)
            );
            if (201 !== $response->getStatusCode()) {
                throw new \Exception($response->getReasonPhrase());
            }
            return new Discount(json_decode($response->getBody(), true));
        } catch (\Exception $exception) {
            $this->logger->warning("Discount creation error. " . $exception->getMessage());
        }
    }

    public function viewDiscount($code)
    {
        try {
            $response = $this->browser->get(
                $this->serviceUrl . "/" . $code
            );
            if (201 !== $response->getStatusCode()) {
                throw new \Exception($response->getReasonPhrase());
            }
            return new Discount(json_decode($response->getBody(), true));
        } catch (\Exception $exception) {
            $this->logger->warning("Discount request error");
        }
    }
}