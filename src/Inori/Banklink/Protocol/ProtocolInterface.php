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
     * @param type $orderId
     * @param type $sum
     * @param type $message
     * @param type $language
     * @param type $currency
     *
     * @return \Inori\Banklink\PaymentRequest
     */
    function preparePaymentRequest($orderId, $sum, $message = '', $language = 'EST', $currency = 'EUR');

    /**
     *
     * @param array $responseData
     *
     * @return \Inori\Banklink\PaymentResponse
     */
    function preparePaymentResponse(array $responseData);

    /**
     * Data should be filtered prior to calling this method
     *
     * @param array $response Response data
     *
     * @return array
     */
    function verifyPaymentResponse(array $response);
}