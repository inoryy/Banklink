<?php

namespace Inori\Banklink;

use Inori\Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Swedbank bank using iPizza protocol for communication
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
class Swedbank extends Banklink
{
    public function __construct(iPizza $protocol)
    {
        parent::__construct($protocol);
    }

    protected function getRequestUrl()
    {
        return 'http://example.com';
    }

    protected function getProtocolVersion()
    {
        return '008';
    }
}