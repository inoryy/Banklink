<?php

namespace Inori\Test\Banklink\iPizza;

use Inori\Banklink\iPizza\iPizza;

/**
 * iPizza protocol test
 *
 * Private/public keys in use are SEB test keys
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  15.01.2012
 */
class iPizzaTest extends \PHPUnit_Framework_TestCase
{
    private $object;

    public function setUp()
    {
        $this->object = new iPizza(
            '123456',
            'Test Testov',
            '654321',
            __DIR__.'/Fixture/private_key.pem',
            __DIR__.'/Fixture/public_key.pem',
            'http://www.google.com',
            'http://www.google.com'
        );
    }

    public function testPreparePaymentRequest()
    {
        $expected = array(
          'VK_SERVICE' => '1001',
          'VK_VERSION' => '008',
          'VK_SND_ID'  => '123456',
          'VK_STAMP'   => '1',
          'VK_AMOUNT'  => '100',
          'VK_CURR'    => 'EUR',
          'VK_ACC'     => '654321',
          'VK_NAME'    => 'Test Testov',
          'VK_REF'     => '13',
          'VK_MSG'     => 'Test payment',
          'VK_CHARSET' => 'UTF-8',
          'VK_RETURN'  => 'http://www.google.com',
          'VK_CANCEL'  => 'http://www.google.com',
          'VK_LANG'    => 'ENG',
          'VK_MAC'     => 'J9S5amcEfXoItiM3GA5rSDksYlY5wEshgD1MirRcWmu77F1e4zx+1EndSjdgiEKNdHkncyFXH77WWhEVNaNm/wA0XjIs/04Oo5G+CprDWNR1H7iYjgLjVGsFI3fyxiYNIocSO0Dr0CG9SGFq9pxj7zHo+z1X7+38ekZRhbNippM='
        );

        $request = $this->object->preparePaymentRequest('1', 'Test payment', '100', 'ENG', 'EUR');

        $this->assertEquals($expected, $request);
    }
}