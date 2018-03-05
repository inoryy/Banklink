<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for SEB bank using iPizza protocol for communication
 * For specs see http://seb.ee/en/business/collection-payments/collection-payments-web/bank-link-specification
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * 
 * @since  20.02.2015
 */
class SEB extends Banklink
{
    protected $productionRequestUrl = 'https://www.seb.ee/cgi-bin/unet3.sh/un3min.r';
    protected $testRequestUrl = 'https://pangalink.net/banklink/seb-common';

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