<?php

namespace Inori\Banklink\Protocol\iPizza;

/**
 * List of all fields used by iPizza protocol
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  10.01.2011
 */
final class Fields
{
    // Order data
    const SERVICE_ID        = 'VK_SERVICE';
    const SUM               = 'VK_AMOUNT';
    const ORDER_ID          = 'VK_STAMP';
    const ORDER_REFERENCE   = 'VK_REF';
    const CURRENCY          = 'VK_CURR';
    const DESCRIPTION       = 'VK_MSG';
    const USER_LANG         = 'VK_LANG';

    // Seller (site owner) info
    const SELLER_ID                = 'VK_SND_ID';
    const SELLER_NAME              = 'VK_NAME';
    const SELLER_BANK_ACC          = 'VK_ACC';

    // data provided in response
    const SELLER_ID_RESPONSE       = 'VK_REC_ID';
    const SELLER_NAME_RESPONSE     = 'VK_REC_NAME';
    const SELLER_BANK_ACC_RESPONSE = 'VK_REC_ACC';
    const SENDER_NAME              = 'VK_SND_NAME';
    const SENDER_BANK_ACC          = 'VK_SND_ACC';
    const TRANSACTION_ID           = 'VK_T_NO';
    const TRANSACTION_DATE         = 'VK_T_DATE';

    // Callback URLs
    const SUCCESS_URL       = 'VK_RETURN';
    const CANCEL_URL        = 'VK_CANCEL';

    // Request configs
    // This data will most likely be static
    const PROTOCOL_VERSION  = 'VK_VERSION';
    const CHARSET           = 'VK_CHARSET';
    const ENCODING          = 'VK_ENCODING';

    const SIGNATURE         = 'VK_MAC';

    /**
     * Can't instantiate this class
     */
    private function __construct() {}
}