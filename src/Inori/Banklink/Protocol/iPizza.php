<?php

namespace Inori\Banklink\Protocol;

use Inori\Banklink\Protocol\iPizza\Fields,
    Inori\Banklink\Protocol\iPizza\Services;

use Inori\Banklink\Request,
    Inori\Banklink\Response;

use Inori\Banklink\Protocol\Util\ProtocolUtils;


/**
 * This class implements iPizza protocol support
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
class iPizza implements ProtocolInterface
{
    protected $publicKey;
    protected $privateKey;

    protected $sellerId;
    protected $sellerName;
    protected $sellerAccountNumber;

    protected $successUrl;
    protected $failureUrl;

    protected $protocolVersion = '008';
    protected $requestUrl      = 'http://example.com';

    protected $charset         = 'UTF-8'; // move to seb/swedbank

    /**
     * initialize basic data that will be used for all issued service requests
     *
     * @param string  $sellerId
     * @param string  $sellerName
     * @param integer $sellerAccNum
     * @param string  $privateKey    Private key location
     * @param string  $publicKey     Public key (certificate) location
     * @param string  $successUrl
     * @param string  $failureUrl
     */
    public function __construct($sellerId, $sellerName, $sellerAccNum, $privateKey, $publicKey, $successUrl, $failureUrl)
    {
        $this->successUrl          = $successUrl;
        $this->failureUrl          = $failureUrl;
        $this->sellerId            = $sellerId;
        $this->sellerName          = $sellerName;
        $this->sellerAccountNumber = $sellerAccNum;

        $this->publicKey           = $publicKey;
        $this->privateKey          = $privateKey;
    }

    /**
     * @see Protocol::preparePaymentRequest()
     */
    public function preparePaymentRequest($orderId, $sum, $message = '', $language = 'EST', $currency = 'EUR')
    {
        $requestData = $this->preparePaymentRequestData($orderId, $sum, $message, $language, $currency);

        return new Request\PaymentRequest($this->requestUrl, $requestData);
    }

    public function preparePaymentResponse(array $responseData)
    {
        ;
    }

    /**
     * @see Protocol::handlePaymentResponse()
     */
    public function verifyPaymentResponse(array $response)
    {
        $checksum = $this->generateHash($response);

        $keyId = openssl_pkey_get_public('file://'.$this->publicKey);
        $result = openssl_verify($checksum, base64_decode($response[Fields::SIGNATURE]), $keyId);
        openssl_free_key($keyId);

        return (boolean) $result;
    }

    /**
     */
    protected function preparePaymentRequestData($orderId, $sum, $message = '', $language = 'EST', $currency = 'EUR')
    {
        $data = array();

        $data[Fields::SERVICE_ID]       = Services::PAYMENT_REQUEST;
        $data[Fields::PROTOCOL_VERSION] = $this->protocolVersion;
        $data[Fields::SELLER_ID]        = $this->sellerId;
        $data[Fields::ORDER_ID]         = $orderId;
        $data[Fields::SUM]              = $sum;
        $data[Fields::CURRENCY]         = $currency;
        $data[Fields::SELLER_BANK_ACC]  = $this->sellerAccountNumber;
        $data[Fields::SELLER_NAME]      = $this->sellerName;
        $data[Fields::ORDER_REFERENCE]  = ProtocolUtils::generateOrderReference($orderId);
        $data[Fields::DESCRIPTION]      = $message;
        $data[Fields::CHARSET]          = $this->charset; // Move to: SEB
        $data[Fields::ENCODING]         = $this->charset; // Move to: Swedbank
        $data[Fields::SUCCESS_URL]      = $this->successUrl;
        $data[Fields::FAILURE_URL]      = $this->failureUrl;
        $data[Fields::USER_LANG]        = $language;

        $data[Fields::SIGNATURE]        = $this->getRequestSignature($data, $this->privateKey);

        return $data;
    }

    /**
     *
     * @param array  $data
     * @param string $key
     * @return string
     */
    protected function getRequestSignature($data, $key)
    {
        $checksum = $this->generateHash($data);

        $keyId = openssl_get_privatekey('file://'.$key);
        openssl_sign($checksum, $signature, $keyId);
        openssl_free_key($keyId);

        $result = base64_encode($signature);

        return $result;
    }

    /**
     *
     * @param array $data
     *
     * @return string
     */
    protected function generateHash(array $data)
    {
        $id = $data[Fields::SERVICE_ID];

        $checksum = '';
        foreach (Services::getFieldsForService($id) as $fieldName) {
            if (!isset($data[$fieldName])) {
                throw new \LogicException(sprintf('Cannot generate %s service hash without %s field', $id, $fieldName));
            }

            $content = $data[$fieldName];
            $checksum .= str_pad(strlen($content), 3, '0', STR_PAD_LEFT) . $content;
        }

        return $checksum;
    }
}