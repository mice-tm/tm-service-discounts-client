<?php
namespace micetm\Clients\ServiceDiscounts\api;

use Buzz\Browser;
use Buzz\Client\FileGetContents;
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
        $this->serviceUrl = $serviceUrl . 'api/v2/discounts';
        $this->logger = $logger;
        $this->browser = new Browser(new FileGetContents([]));
    }

    public function createNewDiscount($params = [])
    {
        try {
            $response = $this->browser->post(
                $this->serviceUrl,
                [
                    'Authorization' => $this->accessToken
                ],
                json_encode($params)
            );
        } catch (\Exception $exception) {
            $this->logger->warning("Discount creation error");
        }
    }

    public function viewDiscount($code)
    {
        try {
            $response = $this->browser->get(
                $this->serviceUrl . "/" . $code
            );
        } catch (\Exception $exception) {
            $this->logger->warning("Discount request error");
        }
    }
}