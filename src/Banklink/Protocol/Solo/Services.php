<?php

namespace Banklink\Protocol\Solo;

/**
 * Solo services helper class
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  25.11.2012
 */
final class Services
{
    /**
     * Get array of mandatory fields for payment request
     *
     * @return array
     */
    public static function getPaymentFields()
    {
        return array(
            Fields::PROTOCOL_VERSION,
            Fields::ORDER_ID,
            Fields::SELLER_ID,
            Fields::SUM,
            Fields::ORDER_REFERENCE,
            Fields::TRANSACTION_DATE,
            Fields::CURRENCY,
        );
    }

    /**
     * Can't instantiate this class
     */
    private function __construct() {}
}