<?php

namespace Inori\Banklink\Protocol;

use Inori\Banklink\Protocol\iPizza\Fields,
    Inori\Banklink\Protocol\iPizza\Services;

use Inori\Banklink\Response\PaymentResponse;

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
    public function __construct($sellerId, $sellerName, $sellerAccNum, $privateKey, $publicKey, $endpointUrl, $version = '008')
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
     *
     * @param type  $orderId
     * @param type  $sum
     * @param type  $message
     * @param type  $language
     * @param type  $currency
     * @return type
     */
    public function preparePaymentRequestData($orderId, $sum, $message = '', $language = 'EST', $currency = 'EUR')
    {
        $requestData = array(
            Fields::SERVICE_ID       => Services::PAYMENT_REQUEST,
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
            Fields::USER_LANG        => $language
        );

        $requestData[Fields::SIGNATURE] = $this->getRequestSignature($requestData, $this->privateKey);

        return $requestData;
    }

    /**
     *
     * @param array $responseData
     * @return \Inori\Banklink\Response\PaymentResponse
     * @throws \InvalidArgumentException
     */
    public function handleResponse(array $responseData)
    {
        $service = $responseData[Fields::SERVICE_ID];
        if (in_array($service, Services::getPaymentServices())) {
            return $this->handlePaymentResponse($responseData);
        }

        throw new \InvalidArgumentException('Unsupported service with id: '.$service);
    }

    /**
     *
     * @param array $responseData
     * @return \Inori\Banklink\Response\PaymentResponse
     */
    protected function handlePaymentResponse(array $responseData)
    {
        $status = $responseData[Fields::SERVICE_ID] == Services::PAYMENT_SUCCESS ? PaymentResponse::STATUS_SUCCESS : PaymentResponse::STATUS_CANCEL;
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