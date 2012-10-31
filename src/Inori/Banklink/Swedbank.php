<?php

namespace Inori\Banklink;

use Inori\Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Swedbank using iPizza protocol for communication
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
class Swedbank extends Banklink
{
    protected $requestUrl = 'http://swedbank.ee';

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
            'VK_ENCODING' => 'UTF-8'
        );
    }
}