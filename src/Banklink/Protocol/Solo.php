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

    protected $keyVersion;

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
    public function __construct($sellerId, $privateKey, $endpointUrl, $sellerName = null, $sellerAccNum = null, $algorithm = 'sha256', $version = '0003', $keyVersion = '0001')
    {
        $this->sellerId            = $sellerId;
        $this->sellerName          = $sellerName;
        $this->sellerAccountNumber = $sellerAccNum;
        $this->endpointUrl         = $endpointUrl;

        $this->privateKey          = $privateKey;

        $this->algorithm           = $algorithm;

        $this->protocolVersion     = $version;
        $this->keyVersion          = $keyVersion;
    }

    /**
     * Determine which response exactly by service id, if it's supported then call related internal method
     *
     * @param array  $responseData
     * @param string $inputEncoding Will most likely always be ISO-8859-1
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
     * @param string   $outputEncoding Will most likely always be ISO-8859-1
     * @param string   $language
     * @param string   $currency
     *
     * @return array
     */
    public function preparePaymentRequestData($orderId, $sum, $message, $outputEncoding, $language = 'EST', $currency = 'EUR')
    {
        $requestData = array(
            Fields::PROTOCOL_VERSION    => $this->protocolVersion,
            Fields::MAC_KEY_VERSION     => $this->keyVersion,
            Fields::SELLER_ID           => $this->sellerId,
            Fields::ORDER_ID            => $orderId,
            Fields::SUM                 => $sum,
            Fields::CURRENCY            => $currency,
            Fields::ORDER_REFERENCE     => ProtocolUtils::generateOrderReference($orderId),
            Fields::DESCRIPTION         => $message,
            Fields::SUCCESS_URL         => $this->endpointUrl,
            Fields::CANCEL_URL          => $this->endpointUrl,
            Fields::REJECT_URL          => $this->endpointUrl,
            Fields::USER_LANG           => $this->getLanguageCodeForString($language),
            Fields::TRANSACTION_DATE    => 'EXPRESS',
            Fields::TRANSACTION_CONFIRM => 'YES'
        );

        // Solo protocol doesn't require seller name/account, unless it's different than default one specified
        if ($this->sellerName && $this->sellerAccountNumber) {
            $requestData[Fields::SELLER_NAME]     = $this->sellerName;
            $requestData[Fields::SELLER_BANK_ACC] = $this->sellerAccountNumber;
        }

        $requestData = ProtocolUtils::convertValues($requestData, 'UTF-8', $outputEncoding);

        $requestData[Fields::SIGNATURE] = $this->getRequestSignature($requestData);

        return $requestData;
    }

    /**
     * Generate request signature built with mandatory request data and private key
     *
     * @todo currently only supports payment signature generation
     *
     * @param array  $data
     * @param string $encoding
     *
     * @return string
     *
     * @throws \LogicException
     */
    protected function getRequestSignature($data)
    {
        return $this->generateHash($data, Services::getPaymentFields());
    }

    /**
     * Generate request/response hash based on mandatory fields
     *
     * @param array  $data
     * @param array  $fields
     *
     * @return string
     *
     * @throws \LogicException
     */
    protected function generateHash(array $data, array $fields)
    {
        $string = '';
        foreach ($fields as $fieldName) {
            if (!isset($data[$fieldName])) {
                throw new \LogicException(sprintf('Cannot generate payment service hash without %s field', $fieldName));
            }

            $string .= $data[$fieldName].'&';
        }
        $string .= $this->privateKey.'&';

        return strtoupper(hash($this->algorithm, $string));
    }

    /**
     * Get language code for a given string, somewhat standardizing methods with iPizza protocol
     *
     * @param string $string
     *
     * @return integer
     *
     * @throws \InvalidArgumentException
     */
    protected function getLanguageCodeForString($string)
    {
       $codes = array('ENG' => 3, 'EST' => 4, 'LAT' => 6, 'LIT' => 7);

       if (!isset($codes[$string])) {
           throw new \InvalidArgumentException(sprintf('This language string (%s) is not supported', $string));
       }

       return $codes[$string];
    }
}