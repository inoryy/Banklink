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
    const SERVICE_ID        = 'VK_SERVICE';
    const VERSION           = 'VK_VERSION';
    const RECIEVER_ID       = 'VK_SND_ID';
    const ORDER_ID          = 'VK_STAMP';
    const SUM               = 'VK_AMOUNT';
    const CURRENCY          = 'VK_CURR';
    const RECIEVER_BANK_ACC = 'VK_ACC';
    const RECIEVER_NAME     = 'VK_NAME';
    const ORDER_REFERENCE   = 'VK_REF';
    const DESCRIPTION       = 'VK_MSG';
    const CHARSET           = 'VK_CHARSET';
    const CHECKSUM          = 'VK_MAC';
    const SUCCESS_URL       = 'VK_RETURN';
    const CANCEL_URL        = 'VK_CANCEL';
    const USER_LANG         = 'VK_LANG';

    /**
     * Can't instantiate this class
     */
    private function __construct()
    {

    }
}