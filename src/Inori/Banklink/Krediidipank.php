<?php

namespace Inori\Banklink;

use Inori\Banklink\Protocol\iPizza;

/**
 * Banklink implementation for LHV bank using iPizza protocol for communication
 * For specs see http://www.krediidipank.ee/business/settlements/bank-link/tehniline_kirjeldus_inglise.pdf
 *
 * @author Roman Marintsenko <roman.marintsenko@gmail.com>
 * @since  1.11.2012
 */
class Krediidipank extends Banklink
{
    protected $requestUrl = 'http://krediidipank.ee';

    /**
     * Force iPizza protocol
     *
     * @param \Inori\Banklink\iPizza $protocol
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