<?php

namespace Banklink;

use Banklink\Request\PaymentRequest;

use Banklink\Protocol\ProtocolInterface;

/**
 * General abstract class that defines public API for all banklink implementations
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  11.01.2012
 */
abstract class Banklink
{
    protected $protocol;

    protected $requestUrl;
    protected $testRequestUrl;

    protected $requestEncoding = 'UTF-8';
    protected $responseEncoding = 'ISO-8859-1';

    /**
     * @param \Banklink\Protocol\ProtocolInterface $protocol
     * @param boolean                              $testMode
     * @param string | null                        $requestUrl
     */
    public function __construct(ProtocolInterface $protocol, $testMode = false, $requestUrl = null)
    {
        $this->protocol = $protocol;

        if ($requestUrl && !$testMode) {
            $this->requestUrl = $requestUrl;
        } else if ($testMode) {
            $this->requestUrl = $this->testRequestUrl;
        }
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

        return new PaymentRequest($this->requestUrl, $requestData);
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
        return $this->responseEncoding;
    }

    /**
     * Get array of any additional fields not directly supported by protocol (ex. encoding)
     *
     * @return array
     */
    abstract protected function getAdditionalFields();
}