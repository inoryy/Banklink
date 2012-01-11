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
     * @param integer $orderId
     *
     * @return integer
     */
    public function generateOrderReference($orderId)
    {
        $rsMultiplier = array(7, 3, 1);
        $ixCurrentMultiplier = 0;

        for ($i = strlen($orderId) - 1; $i >= 0; $i--) {
            $rsProduct[$i] = substr($orderId, $i, 1) * $rsMultiplier[$ixCurrentMultiplier];
            if ($ixCurrentMultiplier == 2) {
                $ixCurrentMultiplier = 0;
            } else {
                $ixCurrentMultiplier++;
            }
        }

        $sumProduct = 0;
        foreach ($rsProduct as $product) {
            $sumProduct += $product;
        }

        if ($sumProduct % 10 == 0) {
            $ixReference = 0;
        } else {
            $ixReference = 10 - ($sumProduct % 10);
        }

        return $orderId . $ixReference;
    }
}