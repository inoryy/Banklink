<?php

namespace Inori\Test\Banklink\iPizza;

use Inori\Banklink\iPizza\iPizza;

/**
 * iPizza protocol test
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  15.01.2012
 */
class iPizzaTest extends \PHPUnit_Framework_TestCase
{
    private $iPizza;

    public function setUp()
    {
        $this->iPizza = new iPizza(
            'uid258629',
            'Test Testov',
            '119933113300',
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
          'VK_SND_ID'  => 'uid258629',
          'VK_STAMP'   => '1',
          'VK_AMOUNT'  => '100',
          'VK_CURR'    => 'EUR',
          'VK_ACC'     => '119933113300',
          'VK_NAME'    => 'Test Testov',
          'VK_REF'     => '13',
          'VK_MSG'     => 'Test payment',
          'VK_CHARSET' => 'UTF-8',
          'VK_ENCODING'=> 'UTF-8',
          'VK_RETURN'  => 'http://www.google.com',
          'VK_CANCEL'  => 'http://www.google.com',
          'VK_LANG'    => 'ENG',
          'VK_MAC'     => 'g4SMbCZEbxSXF7qx8ggcRHTyWOx4Dqkb0eM6atoEC5A12SAlWDgIw5TnB319KtreUcEubrjZz9z4NQgVrSieoOX9yr3G7ciLopGaoajAr6RA9RTYP0QDoArTuDKBqFwRT6D+erTggu9Dz3G/dQKlL9SCQtUxV6yCHp0cLgzYmtUGXoC7x4WnP1NuJZwlBnJI3acsCNyw5gTnEHle0Xd2OElH84aKlItqSsPbFirWhZRLfLy8uyiwSseChnTnDXCINyFLypHNTvvn+DaE8m+nyDkL4Jt3L2rciYkLPuoXSY3JGXTzjS7TkpOPUEtBQZ65ZylltduAeknxocvSZYUskA=='
        );

        $request = $this->iPizza->preparePaymentRequest('1', 'Test payment', '100', 'ENG', 'EUR');

        $this->assertEquals($expected, $request);
    }

    public function testVerifyPaymentResponseCorrect()
    {
        $response = array(
            'VK_SERVICE'  => '1101',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'GENIPIZZA',
            'VK_REC_ID'   => 'uid258629',
            'VK_STAMP'    => '1',
            'VK_T_NO'     => '17944',
            'VK_AMOUNT'   => '100',
            'VK_CURR'     => 'EUR',
            'VK_REC_ACC'  => '119933113300',
            'VK_REC_NAME' => 'Test Testov',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_T_DATE'   => '30.10.2012',
            'VK_ENCODING' => 'UTF-8',
            'VK_AUTO'     => 'N',
            'VK_SND_NAME' => 'Test Account Owner',
            'VK_SND_ACC'  => '221234576897',
            'VK_MAC'      => 'eOhoii13T3KdtNl5YrTvV/RPIaVuml1eCjzrkL3NwhQ45qcrdwKmiRYzv47phfauU6BrTJo7nz2LygrjNMNLjfJQZ62GrGRzn5KqhitJ3YAAdZ8lQr2tY9voV1qa8ZevIPqu9yQg2W0zDVI4o4itB7X/BkbhheMbIhYBPuikAQDRUZqIYUVUil6DrB98Tk8xc3rJoouJkuS8OqOuGRUTNywG49834CmV2PZEi7NHj9lXmXtuDlDWVUdHBXKx6nVsgTLIWRDMFTvDfL2A/OXiNNklkYzEj4DWpaCd3CJyfIjD0xtdA4g+BRRObhqiYN0sBddBa10NMp5wUoflGwfcpw=='
        );

        $this->assertTrue($this->iPizza->verifyPaymentResponse($response));
    }
}