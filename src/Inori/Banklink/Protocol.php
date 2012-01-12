<?php

namespace Inori\Banklink;

/**
 * Description of Protocol
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
interface Protocol
{
    function prepareRequest();

    function validateData();
}