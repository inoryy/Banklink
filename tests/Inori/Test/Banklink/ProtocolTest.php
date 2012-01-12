<?php

namespace Inori\Test\Banklink;

/**
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
class ProtocolTest extends \PHPUnit_Framework_TestCase
{
    private $object;

    public function setUp()
    {
        // really long and weird way to disable original constructor for mocked abstract class...
        $stub = $this->getMockForAbstractClass('Inori\Banklink\Protocol', array(), '', false);
        $stub->expects($this->any())
             ->method('generateOrderReference');

        $this->object = $stub;
    }

    /**
     * Test for normal checksum
     *
     * @covers Inori\Banklink\Protocol::generateOrderReference
     */
    public function testGenerateOrderReference()
    {
        $orderId  = 3425235672;
        $orderRef = 34252356727;

        $this->assertEquals($orderRef, $this->object->generateOrderReference($orderId));
    }

    /**
     * Test for 0 checksum
     *
     * @covers Inori\Banklink\Protocol::generateOrderReference
     */
    public function testGenerateOrderReference0Checksum()
    {
        $orderId  = 1010;
        $orderRef = 10100;

        $this->assertEquals($orderRef, $this->object->generateOrderReference($orderId));
    }

    /**
     * Test for too long/too short id
     *
     * @expectedException InvalidArgumentException
     *
     * @covers Inori\Banklink\Protocol::generateOrderReference
     */
    public function testGenerateOrderReferenceValidation()
    {
        $this->object->generateOrderReference('');
        $this->object->generateOrderReference(12345678901234567890);
    }
}