<?php

namespace Inori\Banklink\Protocol;

/**
 * Description of Protocol
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
interface ProtocolInterface
{
    /**
     * Prepares array of data for a payment request (Service id: 1001)
     *
     * @param integer $orderId
     * @param string  $message
     * @param float   $sum
     * @param string  $language
     * @param string  $currency
     *
     * @return array
     */
    public function preparePaymentRequest($orderId, $message, $sum, $language, $currency = 'EUR');

    /**
     * Data should be filtered prior to calling this method
     *
     * @param array $response Response data
     *
     * @return array
     */
    public function verifyPaymentResponse(array $response);
}