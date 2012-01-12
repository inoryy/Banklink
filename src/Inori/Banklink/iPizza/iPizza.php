<?php

namespace Inori\Banklink\iPizza;

use Inori\Banklink\Protocol;

/**
 * This class implements iPizza protocol support
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
class iPizza extends Protocol
{
    private $privateKeyLocation;
    private $publicKeyLocation;

    private $sellerId;
    private $sellerName;
    private $sellerAccountNumber;

    private $successUrl;
    private $failureUrl;

    private $protocolVersion = '008';
    private $charset         = 'UTF-8';



    /**
     * initialize basic data that will be used for all issued service requests
     */
    public function __construct($sellerId, $sellerName, $sellerAccNum, $privKeyLoc, $pubKeyLoc, $successUrl, $failureUrl)
    {
        $this->successUrl          = $successUrl;
        $this->failureUrl          = $failureUrl;
        $this->sellerId            = $sellerId;
        $this->sellerName          = $sellerName;
        $this->sellerAccountNumber = $sellerAccNum;
        $this->publicKeyLocation   = $pubKeyLoc;
        $this->privateKeyLocation  = $privKeyLoc;
    }

    /**
     * @see Protocol::prepareRequest()
     */
    public function prepareRequest($serviceId, $orderId, $message, $sum, $language, $currency = 'EUR')
    {

    }

    public function validateData()
    {

    }

    protected function generateChecksum()
    {

    }
}