<?php

namespace Inori\Banklink;

/**
 * Description of Protocol
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
abstract class Protocol
{
    /**
     *
     *
     * @param string  $serviceId
     * @param integer $orderId
     * @param string  $message
     * @param float   $sum
     * @param string  $language
     * @param string  $currency
     */
    abstract public function prepareRequest($serviceId, $orderId, $message, $sum, $language, $currency = 'EUR');

    abstract public function validateData();

    abstract protected function generateChecksum();

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