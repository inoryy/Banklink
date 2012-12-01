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
     * Get array of mandatory fields for successful payment response
     *
     * @return array
     */
    public static function getPaymentResponseSuccessFields()
    {
        return array(
            Fields::PROTOCOL_VERSION_RESPONSE,
            Fields::ORDER_ID_RESPONSE,
            Fields::ORDER_REFERENCE_RESPONSE,
            Fields::PAYMENT_CODE,
        );
    }

    /**
     * Get array of mandatory fields for cancelled payment response
     *
     * @return array
     */
    public static function getPaymentResponseCancelFields()
    {
        return array(
            Fields::PROTOCOL_VERSION_RESPONSE,
            Fields::ORDER_ID_RESPONSE,
            Fields::ORDER_REFERENCE_RESPONSE,
        );
    }

    /**
     * Can't instantiate this class
     */
    private function __construct() {}
}