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
    protected $privateKey;

    protected $sellerId;
    protected $sellerName;
    protected $sellerAccountNumber;

    protected $endpointUrl;

    protected $protocolVersion;

    /**
     * Encoding algorithm that is used when calculating MAC signature
     *
     * @var string
     */
    protected $algorithm;

    /**
     * initialize basic data that will be used for all issued service requests
     *
     * @param string  $sellerId
     * @param string  $sellerName
     * @param integer $sellerAccNum
     * @param string  $privateKey    Private key string
     * @param string  $endpointUrl
     * @param string  $algorithm
     * @param string  $version
     */
    public function __construct($sellerId, $privateKey, $endpointUrl, $sellerName = null, $sellerAccNum = null, $algorithm = 'sha256', $version = '0003')
    {
        $this->sellerId            = $sellerId;
        $this->sellerName          = $sellerName;
        $this->sellerAccountNumber = $sellerAccNum;
        $this->endpointUrl         = $endpointUrl;

        $this->privateKey          = $privateKey;

        $this->algorithm           = $algorithm;

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
        return null;
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
        return array();
    }

    /**
     * Get language code for a given string, somewhat standardizing methods with iPizza protocol
     *
     * ENG = 3
     * EST = 4
     * LAT = 6
     * LIT = 7
     *
     * @param string $string
     *
     * @return integer
     *
     * @throws \InvalidArgumentException
     */
    private function getLanguageCodeForString($string)
    {
       $codes = array(
           'ENG' => 3,
           'EST' => 4,
           'LAT' => 6,
           'LIT' => 7
       );

       if (!isset($codes[$string])) {
           throw new \InvalidArgumentException(sprintf('This language string (%s) is not supported', $string));
       }

       return $codes[$string];
    }
}