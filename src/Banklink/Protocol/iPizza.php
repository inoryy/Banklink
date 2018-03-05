<?php

namespace Banklink\Protocol;

use Banklink\Protocol\iPizza\Fields;
use Banklink\Protocol\iPizza\Services;
use Banklink\Response\PaymentResponse;
use Banklink\Protocol\Util\ProtocolUtils;


/**
 * This class implements iPizza protocol support
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * 
 * @since  20.02.2015
 */
class iPizza implements ProtocolInterface
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
     * @param string  $version
     */
    public function __construct($sellerId, $sellerName, $sellerAccNum, $privateKey, $publicKey, $endpointUrl, $version = '008')
    {
        $this->configure($sellerId, $sellerName, $sellerAccNum, $privateKey, $publicKey, $endpointUrl, $version);
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
        $now = new \DateTime();
        $requestData = array(
            Fields::SERVICE_ID       => Services::PAYMENT_REQUEST_2015,
            Fields::PROTOCOL_VERSION => $this->protocolVersion,
            Fields::SELLER_ID        => $this->sellerId,
            Fields::ORDER_ID         => $orderId,
            Fields::SUM              => $sum,
            Fields::CURRENCY         => $currency,
            Fields::SELLER_BANK_ACC  => $this->sellerAccountNumber,
            Fields::SELLER_NAME      => $this->sellerName,
            Fields::ORDER_REFERENCE  => ProtocolUtils::generateOrderReference($orderId),
            Fields::DESCRIPTION      => $message,
            Fields::SUCCESS_URL      => $this->endpointUrl,
            Fields::CANCEL_URL       => $this->endpointUrl,
            Fields::USER_LANG        => $language,
            Fields::REQUEST_DATETIME => $now->format(\DateTime::ISO8601));

        $requestData = ProtocolUtils::convertValues($requestData, 'UTF-8', $outputEncoding);

        $requestData[Fields::SIGNATURE] = $this->getRequestSignature($requestData);

        return $requestData;
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
        $verificationSuccess = $this->verifyResponseSignature($responseData, $inputEncoding);

        $responseData = ProtocolUtils::convertValues($responseData, $inputEncoding, 'UTF-8');

        $service = $responseData[Fields::SERVICE_ID];
        if (in_array($service, Services::getPaymentServices())) {
            return $this->handlePaymentResponse($responseData, $verificationSuccess);
        }

        throw new \InvalidArgumentException('Unsupported service with id: '.$service);
    }

    /**
     * Prepare payment response instance
     * Some data is only set if response is succesful
     *
     * @param array $responseData
     *
     * @return \Banklink\Response\PaymentResponse
     */
    protected function handlePaymentResponse(array $responseData, $verificationSuccess)
    {
        // if response was verified, try to guess status by service id
        if ($verificationSuccess) {
            $status = $responseData[Fields::SERVICE_ID] == Services::PAYMENT_SUCCESS_2015 ? PaymentResponse::STATUS_SUCCESS : PaymentResponse::STATUS_CANCEL;
        } else {
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
            $response->setTransactionDate(new \DateTime($responseData[Fields::RESPONSE_DATETIME]));
        }

        return $response;
    }

    /**
     * Generate request signature built with mandatory request data and private key
     *
     * @param array  $data
     *
     * @return string
     */
    public function getRequestSignature($data)
    {
        $hash = $this->generateHash($data);

        $keyId = openssl_get_privatekey('file://'.$this->privateKey);
        openssl_sign($hash, $signature, $keyId);
        openssl_free_key($keyId);

        $result = base64_encode($signature);

        return $result;
    }

    /**
     * Verify that response data is correctly signed
     *
     * @param array  $responseData
     * @param string $encoding Response data encoding
     *
     * @return boolean
     */
    public function verifyResponseSignature(array $responseData, $encoding)
    {
        $hash = $this->generateHash($responseData, $encoding);

        $keyId = openssl_pkey_get_public('file://'.$this->publicKey);
        $result = openssl_verify($hash, base64_decode($responseData[Fields::SIGNATURE]), $keyId);
        openssl_free_key($keyId);

        return $result === 1;
    }

    /**
     * Generate request/response hash based on mandatory fields
     *
     * @param array  $data
     * @param string $encoding Data encoding
     *
     * @return string
     *
     * @throws \LogicException
     */
    public function generateHash(array $data, $encoding = 'UTF-8')
    {
        $id = $data[Fields::SERVICE_ID];

        $hash = '';
        foreach (Services::getFieldsForService($id) as $fieldName) {
            if (!isset($data[$fieldName])) {
                throw new \LogicException(sprintf('Cannot generate %s service hash without %s field', $id, $fieldName));
            }

            $content = $data[$fieldName];
            $length = mb_strlen($content, $encoding);

            $hash .= str_pad($length, 3, '0', STR_PAD_LEFT) . $content;
        }

        return $hash;
    }

    /**
     * @param $sellerId
     * @param $sellerName
     * @param $sellerAccNum
     * @param $privateKey
     * @param $publicKey
     * @param $endpointUrl
     * @param $version
     */
    public function configure($sellerId, $sellerName, $sellerAccNum, $privateKey, $publicKey, $endpointUrl, $version = '008')
    {
        $this->sellerId = $sellerId;
        $this->sellerName = $sellerName;
        $this->sellerAccountNumber = $sellerAccNum;
        $this->endpointUrl = $endpointUrl;

        $this->publicKey = $publicKey;
        $this->privateKey = $privateKey;

        $this->protocolVersion = $version;
    }
}