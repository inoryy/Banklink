<?php

namespace Banklink\Protocol\iPizza;

/**
 * List of all fields used by iPizza protocol
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * 
 * @since  20.02.2015
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

    // Callback URLs
    const SUCCESS_URL       = 'VK_RETURN';
    const CANCEL_URL        = 'VK_CANCEL';

    // Request configs
    // This data will most likely be static
    const PROTOCOL_VERSION  = 'VK_VERSION';

    const SIGNATURE         = 'VK_MAC';
    
    //New banklink spec addons
    const REQUEST_DATETIME  = 'VK_DATETIME';
    const RESPONSE_DATETIME = 'VK_T_DATETIME';
    

    /**
     * Can't instantiate this class
     */
    private function __construct() {}
}