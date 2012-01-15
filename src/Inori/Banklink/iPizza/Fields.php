<?php

namespace Inori\Banklink\iPizza;

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
    const SELLER_ID         = 'VK_SND_ID';
    const SELLER_NAME       = 'VK_NAME';
    const SELLER_BANK_ACC   = 'VK_ACC';

    // Callback URLs
    const SUCCESS_URL       = 'VK_RETURN';
    const FAILURE_URL       = 'VK_CANCEL';

    // Request configs
    // This data will most likely be static
    const PROTOCOL_VERSION  = 'VK_VERSION';
    const CHARSET           = 'VK_CHARSET';

    const SIGNATURE         = 'VK_MAC';

    /**
     * Can't instantiate this class
     */
    private function __construct()
    {

    }
}