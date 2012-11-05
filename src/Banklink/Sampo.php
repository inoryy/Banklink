<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Sampo bank using iPizza protocol for communication
 * For specs see https://www.sampopank.ee/public/documents/Pangalink_makse_spetsifikatsioon_eng.pdf
 *               https://www.sampopank.ee/public/documents/Pangalink_autentimise_spetsifikatsioon_eng.pdf
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  1.11.2012
 */
class Sampo extends Banklink
{
    protected $requestUrl = 'https://www2.sampopank.ee/ibank/pizza/pizza';
    protected $testRequestUrl = 'https://pangalink.net/banklink/008/sampo';

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