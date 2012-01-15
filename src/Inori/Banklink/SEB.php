<?php

namespace Inori\Banklink;

/**
 * Banklink implementation for SEB bank using iPizza protocol for communication
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
class SEB extends Banklink
{

    public function handleCallback(array $data)
    {
        return false;
    }

    public function prepareRequestArray()
    {
        return false;
    }

    public function prepareRequestHtmlForm()
    {
        return false;
    }

    
}