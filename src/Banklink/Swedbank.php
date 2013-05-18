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
    protected $testRequestUrl = 'https://pangalink.net/banklink/swedbank';

    /**
     * Force iPizza protocol
     *
     * @param \Banklink\iPizza $protocol
     * @param boolean          $testMode
     * @param string | null    $requestUrl
     */
    public function __construct(iPizza $protocol, $testMode = false, $requestUrl = null)
    {
        parent::__construct($protocol, $testMode, $requestUrl);
    }

    /**
     * @inheritDoc
     */
    protected function getEncodingField()
    {
        return 'VK_ENCODING';
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
            'VK_ENCODING' => $this->requestEncoding
        );
    }
}