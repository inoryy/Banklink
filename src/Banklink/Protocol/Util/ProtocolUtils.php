<?php

namespace Banklink\Protocol\Util;

/**
 * Protocol utilities that are used across all protocols
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  31.10.2012
 */
class ProtocolUtils
{
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
    public static function generateOrderReference($orderId)
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

    /**
     * Convert array values from one encoding to another
     *
     * @param array  $values
     * @param string $inputEncoding
     * @param string $outputEncoding
     *
     * @return array
     */
    public static function convertValues(array $values, $inputEncoding, $outputEncoding)
    {
        return array_map(function($value) use($inputEncoding, $outputEncoding) {
            return mb_convert_encoding($value, $outputEncoding, $inputEncoding);
        }, $values);
    }
}