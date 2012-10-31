<?php

namespace Inori\Banklink;

use Inori\Banklink\Protocol\iPizza;

/**
 * Banklink implementation for SEB bank using iPizza protocol for communication
 *
 * @author Roman Marintsenko <roman.marintsenko@gmail.com>
 * @since  11.01.2012
 */
class SEB extends Banklink
{
    protected $requestUrl = 'http://seb.ee';

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