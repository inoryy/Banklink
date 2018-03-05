<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for LHV bank using iPizza protocol for communication
 * For specs see http://www.lhv.ee/images/docs/Bank_Link_Technical_Specification-EN.pdf
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * @since  1.11.2012
 */
class LHV extends Banklink
{
    protected $productionRequestUrl = 'https://www.lhv.ee/banklink';
    protected $testRequestUrl = 'https://pangalink.net/banklink/lhv';

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
        return 'VK_CHARSET';
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
            'VK_CHARSET' => 'UTF-8'
        );
    }
}