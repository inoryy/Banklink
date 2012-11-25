<?php

namespace Banklink\Protocol;

use Banklink\Protocol\Solo\Fields,
    Banklink\Protocol\Solo\Services;

use Banklink\Response\PaymentResponse;

use Banklink\Protocol\Util\ProtocolUtils;

/**
 * This class implements Solo protocol (mainly used by Nordea bank)
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  25.11.2012
 */
class Solo implements ProtocolInterface
{
    protected $publicKey;
    protected $privateKey;

    protected $sellerId;
    protected $sellerName;
    protected $sellerAccountNumber;

    protected $endpointUrl;

    protected $protocolVersion;

    /**
     * initialize basic data that will be used for all issued service requests
     *
     * @param string  $sellerId
     * @param string  $sellerName
     * @param integer $sellerAccNum
     * @param string  $privateKey    Private key location
     * @param string  $publicKey     Public key (certificate) location
     * @param string  $endpointUrl
     * @param string  $cancelUrl
     */
    public function __construct($sellerId, $sellerName, $sellerAccNum, $privateKey, $publicKey, $endpointUrl, $version = '0003')
    {
        $this->sellerId            = $sellerId;
        $this->sellerName          = $sellerName;
        $this->sellerAccountNumber = $sellerAccNum;
        $this->endpointUrl         = $endpointUrl;

        $this->publicKey           = $publicKey;
        $this->privateKey          = $privateKey;

        $this->protocolVersion     = $version;
    }

    /**
     * Determine which response exactly by service id, if it's supported then call related internal method
     *
     * @param array  $responseData
     * @param string $inputEncoding
     *
     * @return \Banklink\Response\Response
     *
     * @throws \InvalidArgumentException
     */
    public function handleResponse(array $responseData, $inputEncoding)
    {

    }

    /**
     * @param integer  $orderId
     * @param float    $sum
     * @param string   $message
     * @param string   $outputEncoding
     * @param string   $language
     * @param string   $currency
     *
     * @return array
     */
    public function preparePaymentRequestData($orderId, $sum, $message, $outputEncoding, $language = 'EST', $currency = 'EUR')
    {

    }
}