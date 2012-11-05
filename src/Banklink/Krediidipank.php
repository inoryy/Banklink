<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Krediidipank bank using iPizza protocol for communication
 * For specs see http://www.krediidipank.ee/business/settlements/bank-link/tehniline_kirjeldus_inglise.pdf
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  1.11.2012
 */
class Krediidipank extends Banklink
{
    protected $requestUrl = 'https://i-pank.krediidipank.ee/teller/maksa';
    protected $testRequestUrl = 'https://pangalink.net/banklink/008/krediidipank';

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
            'VK_CHARSET' => 'UTF-8'
        );
    }
}