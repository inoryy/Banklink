<?php

namespace Inori\Banklink\Protocol;

use Inori\Banklink\Protocol\iPizza\Fields,
    Inori\Banklink\Protocol\iPizza\Services;

use Inori\Banklink\Request\PaymentRequest,
    Inori\Banklink\Response\PaymentResponse;

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

    protected $endpointUrl;

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
     * @param string  $endpointUrl
     * @param string  $cancelUrl
     */
    public function __construct($sellerId, $sellerName, $sellerAccNum, $privateKey, $publicKey, $endpointUrl)
    {
        $this->sellerId            = $sellerId;
        $this->sellerName          = $sellerName;
        $this->sellerAccountNumber = $sellerAccNum;
        $this->endpointUrl         = $endpointUrl;

        $this->publicKey           = $publicKey;
        $this->privateKey          = $privateKey;
    }

    /**
     *
     * @param integer $orderId
     * @param float $sum
     * @param string $message
     * @param string $language
     * @param string $currency
     *
     * @return \Inori\Banklink\Request\PaymentRequest
     */
    public function preparePaymentRequest($orderId, $sum, $message = '', $language = 'EST', $currency = 'EUR')
    {
        $requestData = $this->preparePaymentRequestData($orderId, $sum, $message, $language, $currency);

        return new PaymentRequest($this->requestUrl, $requestData);
    }

    /**
     *
     * @param array $responseData
     * @return \Inori\Banklink\Response\PaymentResponse
     */
    public function handlePaymentResponse(array $responseData)
    {
        $status = $responseData[Fields::SERVICE_ID] == Services::PAYMENT_SUCCESS ? PaymentResponse::STATUS_SUCCESS : PaymentResponse::STATUS_CANCELED;
        if (!$this->verifyResponseSignature($responseData)) {
            $status = PaymentResponse::STATUS_ERROR;
        }

        $response = new PaymentResponse($status, $responseData);
        $response->setOrderId($responseData[Fields::ORDER_ID]);

        if (PaymentResponse::STATUS_SUCCESS === $status) {
            $response->setSum($responseData[Fields::SUM]);
            $response->setCurrency($responseData[Fields::CURRENCY]);
            $response->setSenderName($responseData[Fields::SENDER_NAME]);
            $response->setSenderBankAccount($responseData[Fields::SENDER_BANK_ACC]);
            $response->setTransactionId($responseData[Fields::TRANSACTION_ID]);
            $response->setTransactionDate(new \DateTime($responseData[Fields::TRANSACTION_DATE]));
        }

        return $response;
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
        $data[Fields::SUCCESS_URL]      = $this->endpointUrl;
        $data[Fields::CANCEL_URL]       = $this->endpointUrl;
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
     * @see Protocol::handlePaymentResponse()
     */
    protected function verifyResponseSignature(array $response)
    {
        $checksum = $this->generateHash($response);

        $keyId = openssl_pkey_get_public('file://'.$this->publicKey);
        $result = openssl_verify($checksum, base64_decode($response[Fields::SIGNATURE]), $keyId);
        openssl_free_key($keyId);

        return (boolean) $result;
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