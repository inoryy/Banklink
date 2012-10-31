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

    /**
     *
     * @param ProtocolInterface $protocol
     */
    public function __construct(ProtocolInterface $protocol)
    {
        $this->protocol = $protocol;
    }

    public function preparePaymentRequest($orderId, $sum, $message = '', $language = 'EST', $currency = 'EUR')
    {
        $requestData = $this->protocol->preparePaymentRequestData($orderId, $sum, $message, $language, $currency);
        $requestData = array_merge($requestData, $this->getAdditionalFields());

        return new PaymentRequest($this->getRequestUrl(), $requestData);
    }

    /**
     *
     * @param array $responseData Data recieved with a callback request
     */
    public function handleResponse(array $responseData)
    {
        return $this->protocol->handleResponse($responseData);
    }

    abstract protected function getRequestUrl();
    /**
     * Get array of any additional fields not directly supported by protocol (ex. encoding)
     *
     * @return array
     */
    abstract protected function getAdditionalFields();
}