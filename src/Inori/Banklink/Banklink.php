<?php

namespace Inori\Banklink;

use Inori\Banklink\Request\PaymentRequest;

use Inori\Banklink\Protocol\ProtocolInterface;

/**
 * General abstract class that defines public API for all banklink implementations
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
abstract class Banklink
{
    protected $protocol;

    protected $requestUrl;

    /**
     * @param \Inori\Banklink\Protocol\ProtocolInterface $protocol
     * @param string | null                              $requestUrl
     */
    public function __construct(ProtocolInterface $protocol, $requestUrl = null)
    {
        $this->protocol = $protocol;

        if ($requestUrl) {
            $this->requestUrl = $requestUrl;
        }
    }

    /**
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
        $requestData = $this->protocol->preparePaymentRequestData($orderId, $sum, $message, $language, $currency);
        $requestData = array_merge($requestData, $this->getAdditionalFields());

        return new PaymentRequest($this->requestUrl, $requestData);
    }

    /**
     *
     * @param array $responseData
     * @return \Inori\Banklink\Response\Response
     */
    public function handleResponse(array $responseData)
    {
        return $this->protocol->handleResponse($responseData);
    }

    /**
     * Get array of any additional fields not directly supported by protocol (ex. encoding)
     *
     * @return array
     */
    abstract protected function getAdditionalFields();
}