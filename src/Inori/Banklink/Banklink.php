<?php

namespace Inori\Banklink;

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
     * @param Protocol $protocol
     */
    public function __construct(Protocol $protocol)
    {
        $this->protocol = $protocol;
    }

    public function preparePaymentRequest($orderId, $message, $sum, $language, $currency = 'EUR')
    {
        return $this->protocol->preparePaymentRequest($orderId, $message, $sum, $language, $currency);
    }

    /**
     *
     * @param array $data Data recieved with a callback request
     */
    abstract public function handleCallback(array $data);
}