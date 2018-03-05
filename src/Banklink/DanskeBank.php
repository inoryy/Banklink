<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Sampo bank using iPizza protocol for communication
 * For specs see https://www.danskebank.ee/public/documents/Pangalink_makse_spetsifikatsioon_eng.pdf
 *               https://www.danskebank.ee/public/documents/Pangalink_autentimise_spetsifikatsioon_eng.pdf
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * 
 * @since  20.02.2015
 */
class DanskeBank extends Banklink
{
    protected $productionRequestUrl = 'https://www2.danskebank.ee/ibank/pizza/pizza';
    protected $testRequestUrl = 'https://pangalink.net/banklink/sampo-common';

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
     * No additional fields
     *
     * @see Banklink::getAdditionalFields()
     *
     * @return array
     */
    protected function getAdditionalFields()
    {
        return array();
    }
}