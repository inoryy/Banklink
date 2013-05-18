<?php

namespace Banklink\Protocol;

use Banklink\Protocol\iPizza\Fields,
    Banklink\Protocol\iPizza\Services;


/**
 * Provides small tweak for Swedbank implementation of iPizza protocol
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  18.05.2013
 */
class SwedbankiPizza extends iPizza
{
    protected function getStringLengthForHash($string)
    {
        return mb_strlen($string, 'UTF-8');
    }
}