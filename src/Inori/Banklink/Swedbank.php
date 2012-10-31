<?php

namespace Inori\Banklink;

/**
 * Banklink implementation for Swedbank bank using iPizza protocol for communication
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
class Swedbank extends Banklink
{
    protected $requestUrl = '';

    public function handleResponse(array $data)
    {
        return false;
    }
}