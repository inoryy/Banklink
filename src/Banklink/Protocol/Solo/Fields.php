<?php

namespace Banklink\Protocol\Solo;

/**
 * List of all fields used by Solo protocol
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  25.11.2011
 */
final class Fields
{
    // Order data
    const SUM               = 'SOLOPMT_AMOUNT';
    const ORDER_ID          = 'SOLOPMT_STAMP';
    const ORDER_REFERENCE   = 'SOLOPMT_REF';
    const CURRENCY          = 'SOLOPMT_CUR';
    const DESCRIPTION       = 'SOLOPMT_MSG';
    const USER_LANG         = 'SOLOPMT_LANGUAGE';

    // Seller (site owner) info
    const SELLER_ID                = 'SOLOPMT_RCV_ID';
    const SELLER_NAME              = 'SOLOPMT_RCV_NAME';
    const SELLER_BANK_ACC          = 'SOLOPMT_RCV_ACCOUNT';

    const TRANSACTION_DATE         = 'SOLOPMT_DATE';
    const TRANSACTION_CONFIRM      = 'SOLOPMT_CONFIRM';

    // data provided in response
    const SELLER_ID_RESPONSE       = 'VK_REC_ID';
    const SELLER_NAME_RESPONSE     = 'VK_REC_NAME';
    const SELLER_BANK_ACC_RESPONSE = 'VK_REC_ACC';
    const SENDER_NAME              = 'VK_SND_NAME';
    const SENDER_BANK_ACC          = 'VK_SND_ACC';
    const TRANSACTION_ID           = 'VK_T_NO';

    // Callback URLs
    const SUCCESS_URL       = 'SOLOPMT_RETURN';
    const CANCEL_URL        = 'SOLOPMT_CANCEL';
    const REJECT_URL        = 'SOLOPMT_REJECT';

    // Request configs
    // This data will most likely be static
    const PROTOCOL_VERSION  = 'SOLOPMT_VERSION';
    const MAC_KEY_VERSION   = 'SOLOPMT_KEYVERS';

    const SIGNATURE         = 'SOLOPMT_MAC';

    /**
     * Can't instantiate this class
     */
    private function __construct() {}
}