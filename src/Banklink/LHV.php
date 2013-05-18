<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for LHV bank using iPizza protocol for communication
 * For specs see http://www.lhv.ee/images/docs/Bank_Link_Technical_Specification-EN.pdf
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  1.11.2012
 */
class LHV extends Banklink
{
    protected $requestUrl = 'https://www.lhv.ee/banklink';
    protected $testRequestUrl = 'https://pangalink.net/banklink/lhv';

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