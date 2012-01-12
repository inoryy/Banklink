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

//    public function __construct(Protocol $protocol)
//    {
//        $this->protocol = $protocol;
//    }

    /**
     *
     * @param array $data Data recieved with a callback request
     */
    abstract public function handleCallback(array $data);

    abstract public function prepareRequestArray();

    abstract public function prepareRequestHtmlForm();

    /**
     * Generates order reference using 7-3-1 algorithm
     *
     * For more info see http://www.pangaliit.ee/en/settlements-and-standards/reference-number-of-the-invoice
     *
     * @param string|integer $orderId Order id
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