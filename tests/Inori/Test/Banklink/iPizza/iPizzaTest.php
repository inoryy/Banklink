<?php

namespace Inori\Test\Banklink\iPizza;

use Inori\Banklink\iPizza\iPizza;

/**
 * iPizza protocol test
 *
 * Test data is taken from SEB Test pack: http://seb.ee/files/upos/manused.zip
 * Not the best way to test abstract protocol, but only real-world data I can use for testing
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
            'testvpos',
            'Keegi',
            '10002050618003',
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
          'VK_SND_ID'  => 'testvpos',
          'VK_STAMP'   => '1',
          'VK_AMOUNT'  => '100',
          'VK_CURR'    => 'EUR',
          'VK_ACC'     => '10002050618003',
          'VK_NAME'    => 'Keegi',
          'VK_REF'     => '13',
          'VK_MSG'     => 'Test payment',
          'VK_CHARSET' => 'UTF-8',
          'VK_RETURN'  => 'http://www.google.com',
          'VK_CANCEL'  => 'http://www.google.com',
          'VK_LANG'    => 'ENG',
          'VK_MAC'     => 'Q1UMrfV8COxm8tyyjBTxBgC5lcfc7bkqM4l2JRVk5aKFq/F0b8kGDyEls31pXbmdjdRGBO6z9HHzfF86GJzkagP5CmRVTK/crOdnZD/cECR9wNmxQ1i5SpNeLOILl+tZK4tQTGxY2Sl8tDd38RUMKe/F1J/39HrKDJLufCuGSXs='
        );

        $request = $this->object->preparePaymentRequest('1', 'Test payment', '100', 'ENG', 'EUR');

        $this->assertEquals($expected, $request);
    }

    public function testVerifyPaymentResponse()
    {
        $response = array(
            'VK_SERVICE'  => '1001',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'testvpos',
            'VK_REC_ID'   => 'Test',
            'VK_STAMP'    => '1',
            'VK_T_NO'     => '',
            'VK_AMOUNT'   => '100',
            'VK_CURR'     => 'EUR',
            'VK_REC_ACC'  => '',
            'VK_REC_NAME' => '',
            'VK_SND_ACC'  => '',
            'VK_SND_NAME' => '',
            'VK_REF'      => '',
            'VK_MSG'      => '',
            'VK_T_DATE'   => ''
        );
    }
}