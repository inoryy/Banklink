<?php

namespace Inori\Banklink\Protocol\iPizza;

/**
 * List of all services available via iPizza
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  10.01.2012
 */
final class Services
{
    // Requests
    const PAYMENT_REQUEST      = '1001';
    const AUTHENTICATE_REQUEST         = '3001';

    // Responses
    const PAYMENT_SUCCESS      = '1101';
    const PAYMENT_CANCEL       = '1901';
    const PAYMENT_ERROR        = '1902';
    const AUTHENTICATE_SUCCESS = '3002';

    /**
     * @param string $serviceId
     */
    public static function getFieldsForService($serviceId)
    {
        switch ($serviceId) {
            case Services::PAYMENT_REQUEST:
                return array(
                    Fields::SERVICE_ID,
                    Fields::PROTOCOL_VERSION,
                    Fields::SELLER_ID,
                    Fields::ORDER_ID,
                    Fields::SUM,
                    Fields::CURRENCY,
                    Fields::SELLER_BANK_ACC,
                    Fields::SELLER_NAME,
                    Fields::ORDER_REFERENCE,
                    Fields::DESCRIPTION
                );
            case Services::PAYMENT_SUCCESS:
                return array(
                    Fields::SERVICE_ID,
                    Fields::PROTOCOL_VERSION,
                    Fields::SELLER_ID,
                    Fields::SELLER_ID_RESPONSE,
                    Fields::ORDER_ID,
                    Fields::TRANSACTION_ID,
                    Fields::SUM,
                    Fields::CURRENCY,
                    Fields::SELLER_BANK_ACC_RESPONSE,
                    Fields::SELLER_NAME_RESPONSE,
                    Fields::SENDER_BANK_ACC,
                    Fields::SENDER_NAME,
                    Fields::ORDER_REFERENCE,
                    Fields::DESCRIPTION,
                    Fields::TRANSACTION_DATE,
                );
            case Services::PAYMENT_CANCEL:
                return array(
                    Fields::SERVICE_ID,
                    Fields::PROTOCOL_VERSION,
                    Fields::SELLER_ID,
                    Fields::SELLER_ID_RESPONSE,
                    Fields::ORDER_ID,
                    Fields::ORDER_REFERENCE,
                    Fields::DESCRIPTION,
                );
            default:
                throw new \InvalidArgumentException('Unsupported service id: '.$serviceId);
        }
    }

    public static function getPaymentServices()
    {
        return array(
            self::PAYMENT_REQUEST,
            self::PAYMENT_SUCCESS,
            self::PAYMENT_CANCEL,
            self::PAYMENT_ERROR
        );
    }

    public static function getAuthenticationServices()
    {
        return array(
            self::AUTHENTICATE_REQUEST,
            self::AUTHENTICATE_SUCCESS
        );
    }

    /**
     * Can't instantiate this class
     */
    private function __construct() {}
}