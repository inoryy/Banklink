<?php

namespace Banklink\Protocol\iPizza;

/**
 * List of all services available via iPizza
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * 
 * @since  20.02.2015
 */
final class Services
{
    // Requests
    const PAYMENT_REQUEST_2015 = '1011';
    const AUTHENTICATE_REQUEST = '3001';

    // Responses
    const PAYMENT_SUCCESS_2015 = '1111';
    const PAYMENT_CANCEL_2015  = '1911';
    const PAYMENT_ERROR        = '1902';
    const AUTHENTICATE_SUCCESS = '3002';

    /**
     * Fetch mandatory fields for a given service (for VK_MAC calculation)
     *
     * @param string $serviceId
     * @return array
     * @throws \InvalidArgumentException
     */
    public static function getFieldsForService($serviceId)
    {
        switch ($serviceId) {
            case Services::PAYMENT_REQUEST_2015:
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
                    Fields::DESCRIPTION,
                    Fields::SUCCESS_URL,
                    Fields::CANCEL_URL,
                    Fields::REQUEST_DATETIME
                );
            case Services::PAYMENT_SUCCESS_2015:
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
                    Fields::RESPONSE_DATETIME
                );
            case Services::PAYMENT_CANCEL_2015:
                return array(
                    Fields::SERVICE_ID,
                    Fields::PROTOCOL_VERSION,
                    Fields::SELLER_ID,
                    Fields::SELLER_ID_RESPONSE,
                    Fields::ORDER_ID,
                    Fields::ORDER_REFERENCE,
                    Fields::DESCRIPTION
                );
            default:
                throw new \InvalidArgumentException('Unsupported service id: '.$serviceId);
        }
    }

    /**
     * Fetch supported payment services
     *
     * @return array
     */
    public static function getPaymentServices()
    {
        return array(
            self::PAYMENT_REQUEST_2015,
            self::PAYMENT_SUCCESS_2015,
            self::PAYMENT_CANCEL_2015,
            self::PAYMENT_ERROR
        );
    }

    /**
     * Fetch supported authentication services
     * //TODO: Not upgraded ATM
     * 
     * @return array
     */
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