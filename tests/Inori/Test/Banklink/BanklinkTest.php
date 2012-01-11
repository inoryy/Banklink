<?php

namespace Inori\Test\Banklink;

/**
 *
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
class BanklinkTest extends \PHPUnit_Framework_TestCase
{
    private $object;

    public function setUp()
    {
        $stub = $this->getMockForAbstractClass('Inori\Banklink\Banklink');
        $stub->expects($this->any())
             ->method('generateOrderReference');

        $this->object = $stub;
    }

    public function testGenerateOrderReference()
    {
        $orderId  = 3425235672;
        $orderRef = 34252356727;

        $this->assertEquals($orderRef, $this->object->generateOrderReference($orderId));
    }
}