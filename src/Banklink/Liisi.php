<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Liisi leasing using iPizza protocol for communication
 *
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * @see https://klient.liisi.ee/static/payment_link_doc/
 * @since  19.07.2016
 */
class Liisi extends Banklink
{
    protected $productionRequestUrl = 'https://klient.liisi.ee/api/ipizza/';
    protected $testRequestUrl = 'https://prelive.liisi.ee:8953/api/ipizza/';

    /**
     * Force iPizza protocol
     *
     * @param iPizza  $protocol
     * @param boolean $testMode
     */
    public function __construct(iPizza $protocol, $testMode = false)
    {
        parent::__construct($protocol, $testMode);
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