<?php

namespace Inori\Banklink;

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
        $protocol->setRequestUrl($this->getRequestUrl());
        $protocol->setProtocolVersion($this->getProtocolVersion());

        $this->protocol = $protocol;
    }

    public function preparePaymentRequest($orderId, $sum, $message = '', $language = 'EST', $currency = 'EUR')
    {
        return $this->protocol->preparePaymentRequest($orderId, $sum, $message, $language, $currency);
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
    abstract protected function getProtocolVersion();
}