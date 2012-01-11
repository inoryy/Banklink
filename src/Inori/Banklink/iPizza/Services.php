<?php

namespace Inori\Banklink\iPizza;

/**
 * List of all services available via iPizza
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  10.01.2012
 */
final class Services
{
    // Requests
    const PAYMENT_REQUEST      = '1001';
    const AUTHENTICATE         = '3001';

    // Responses
    const PAYMENT_SUCCESS      = '1101';
    const PAYMENT_CANCEL       = '1901';
    const PAYMENT_ERROR        = '1902';

    /**
     * Can't instantiate this class
     */
    private function __construct()
    {

    }
}