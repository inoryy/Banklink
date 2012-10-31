<?php

namespace Inori\Banklink;

use Inori\Banklink\Protocol\iPizza;

/**
 * Banklink implementation for SEB bank using iPizza protocol for communication
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
class SEB extends Banklink
{
    protected $requestUrl = 'http://seb.ee';

    /**
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