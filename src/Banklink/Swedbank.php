<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Swedbank using iPizza protocol for communication
 * For specs see https://www.swedbank.ee/static/pdf/business/d2d/paymentcollection/info_banklink_techspec_eng.pdf
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  11.01.2012
 */
class Swedbank extends Banklink
{
    protected $requestUrl = 'https://www.swedbank.ee/banklink';
    protected $testRequestUrl = 'https://pangalink.net/banklink/008/swedbank';

    /**
     * Force iPizza protocol
     *
     * @param \Banklink\iPizza $protocol
     */
    public function __construct(iPizza $protocol)
    {
        parent::__construct($protocol);
    }

    /**
     * Force UTF-8 encoding
     *
     * @see Banklink::getAdditionalFields()
     *
     * @return array
     */
    protected function getAdditionalFields()
    {
        return array(
            'VK_ENCODING' => 'UTF-8'
        );
    }
}