<?php

namespace Inori\Banklink\Protocol;

/**
 * Description of Protocol
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
abstract class Protocol
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
    abstract public function preparePaymentRequest($orderId, $message, $sum, $language, $currency = 'EUR');

    /**
     * Data should be filtered prior to calling this method
     *
     * @param array $response Response data
     *
     * @return array
     */
    abstract public function verifyPaymentResponse(array $response);

    abstract protected function generateChecksum(array $data);

    abstract protected function getRequestSignature($data, $key);

    /**
     * Generates order reference using 7-3-1 algorithm
     *
     * For more info see http://www.pangaliit.ee/en/settlements-and-standards/reference-number-of-the-invoice
     *
     * @param integer $orderId Order id
     *
     * @throws InvalidArgumentException If order id is too long or short
     *
     * @return string
     */
    public function generateOrderReference($orderId)
    {
        $orderId = (string)$orderId;
        $len = strlen($orderId);

        if (1 > $len || 19 < $len) {
            throw new \InvalidArgumentException('OrderId must be between 1 and 19 digits');
        }

        $current = 0;
        $multiplier = array(7, 3, 1);
        $sumProduct = 0;

        for ($i = $len - 1; $i >= 0; $i--) {
            $sumProduct += (int)$orderId[$i] * $multiplier[$current];

            $current = $current < 2 ? ++$current : 0;
        }

        $rounded = ceil($sumProduct/10) * 10;
        $checkSum = $rounded - $sumProduct;

        return $orderId . $checkSum;
    }
}