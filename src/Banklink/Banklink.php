<?php

namespace Banklink;

use Banklink\Request\PaymentRequest;
use Banklink\Protocol\ProtocolInterface;

/**
 * General abstract class that defines public API for all banklink implementations
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 *
 * @since  20.02.2015
 */
abstract class Banklink
{
    protected $isTestMode;
    protected $protocol;

    protected $testRequestUrl;
    protected $productionRequestUrl;

    protected $requestEncoding = 'UTF-8';
    protected $responseEncoding = 'UTF-8';

    /**
     * @param \Banklink\Protocol\ProtocolInterface $protocol
     * @param boolean                              $testMode
     */
    public function __construct(ProtocolInterface $protocol, $testMode = false)
    {
        $this->protocol = $protocol;
        $this->isTestMode = $testMode;
    }

    /**
     * @param integer $orderId
     * @param float   $sum
     * @param string  $message
     * @param string  $language
     * @param string  $currency
     *
     * @return \Banklink\Request\PaymentRequest
     */
    public function preparePaymentRequest($orderId, $sum, $message, $language = 'EST', $currency = 'EUR')
    {
        $requestData = $this->protocol->preparePaymentRequestData($orderId, $sum, $message, $this->requestEncoding, $language, $currency);
        $requestData = array_merge($requestData, $this->getAdditionalFields());

        return new PaymentRequest($this->getRequestUrl(), $requestData);
    }

    /**
     * @param array $responseData
     *
     * @return \Banklink\Response\Response
     */
    public function handleResponse(array $responseData)
    {
        return $this->protocol->handleResponse($responseData, $this->getResponseEncoding($responseData));
    }

    /**
     * Assuming response data may have some additional field to specify encoding, this method can be overriden
     *
     * @param array $responseData
     *
     * @return string
     */
    protected function getResponseEncoding(array $responseData)
    {
        if ($this->getEncodingField() && isset($responseData[$this->getEncodingField()])) {
            return $responseData[$this->getEncodingField()];
        }

        return $this->responseEncoding;
    }

    /**
     * If Bank supports encoding field like VK_CHARSET
     *
     * @return string | null
     */
    protected function getEncodingField()
    {
        return null;
    }

    /**
     * Get array of any additional fields not directly supported by protocol (ex. encoding)
     *
     * @return array
     */
    abstract protected function getAdditionalFields();

    /**
     * @return mixed
     */
    public function getRequestUrl()
    {
        return $this->isTestMode ? $this->testRequestUrl : $this->productionRequestUrl;
    }
}