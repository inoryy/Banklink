<?php

namespace Banklink\Protocol\Util;

use Banklink\Protocol\Util\ProtocolUtils;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  11.01.2012
 */
class ProtocolUtilsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test for normal checksum
     */
    public function testGenerateOrderReference()
    {
        $orderId  = 3425235672;
        $orderRef = 34252356727;

        $this->assertEquals($orderRef, ProtocolUtils::generateOrderReference($orderId));
    }

    /**
     * Test for 0 checksum
     */
    public function testGenerateOrderReference0Checksum()
    {
        $orderId  = 1010;
        $orderRef = 10100;

        $this->assertEquals($orderRef, ProtocolUtils::generateOrderReference($orderId));
    }

    /**
     * Test for too long/too short id
     *
     * @expectedException InvalidArgumentException
     */
    public function testGenerateOrderReferenceValidation()
    {
        ProtocolUtils::generateOrderReference('');
        ProtocolUtils::generateOrderReference(12345678901234567890);
    }
}