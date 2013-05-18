<?php

namespace Banklink;

use Banklink\Swedbank;
use Banklink\Protocol\SwedbankiPizza;

use Banklink\Response\PaymentResponse;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  31.10.2012
 */
class SwedbankTest extends \PHPUnit_Framework_TestCase
{
    private $swedbank;

    public function setUp()
    {
        $protocol = new SwedbankiPizza(
            'uid258629',
            'Test Testov',
            '119933113300',
            __DIR__.'/data/iPizza/private_key.pem',
            __DIR__.'/data/iPizza/public_key.pem',
            'http://www.google.com'
        );

        $this->swedbank = new Swedbank($protocol);
    }

    public function testPreparePaymentRequest()
    {
        $expectedRequestData = array(
          'VK_SERVICE'  => '1001',
          'VK_VERSION'  => '008',
          'VK_SND_ID'   => 'uid258629',
          'VK_STAMP'    => '1',
          'VK_AMOUNT'   => '100',
          'VK_CURR'     => 'EUR',
          'VK_ACC'      => '119933113300',
          'VK_NAME'     => 'Test Testov',
          'VK_REF'      => '13',
          'VK_MSG'      => 'Test payment',
          'VK_RETURN'   => 'http://www.google.com',
          'VK_CANCEL'   => 'http://www.google.com',
          'VK_LANG'     => 'ENG',
          'VK_ENCODING' => 'UTF-8',
          'VK_MAC'      => 'g4SMbCZEbxSXF7qx8ggcRHTyWOx4Dqkb0eM6atoEC5A12SAlWDgIw5TnB319KtreUcEubrjZz9z4NQgVrSieoOX9yr3G7ciLopGaoajAr6RA9RTYP0QDoArTuDKBqFwRT6D+erTggu9Dz3G/dQKlL9SCQtUxV6yCHp0cLgzYmtUGXoC7x4WnP1NuJZwlBnJI3acsCNyw5gTnEHle0Xd2OElH84aKlItqSsPbFirWhZRLfLy8uyiwSseChnTnDXCINyFLypHNTvvn+DaE8m+nyDkL4Jt3L2rciYkLPuoXSY3JGXTzjS7TkpOPUEtBQZ65ZylltduAeknxocvSZYUskA=='
        );

        $request = $this->swedbank->preparePaymentRequest(1, 100, 'Test payment', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request->getRequestData());
        $this->assertEquals('https://www.swedbank.ee/banklink', $request->getRequestUrl());
    }

    public function testHandlePaymentResponseSuccessWithSpecialCharacters()
    {
        $responseData = array(
            'VK_SERVICE'  => '1101',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'GENIPIZZA',
            'VK_REC_ID'   => 'uid258629',
            'VK_STAMP'    => '1',
            'VK_T_NO'     => '18193',
            'VK_AMOUNT'   => '100',
            'VK_CURR'     => 'EUR',
            'VK_REC_ACC'  => '119933113300',
            'VK_REC_NAME' => 'Test Testov',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_T_DATE'   => '06.11.2012',
            'VK_AUTO'     => 'N',
            'VK_ENCODING' => 'ISO-8859-1',
            'VK_SND_NAME' => mb_convert_encoding('Tõõger Leõpäöld', 'ISO-8859-1', 'UTF-8'),
            'VK_SND_ACC'  => '221234567897',
            'VK_MAC'      => 'eK4mEiRhpZ/gz1/4GEaNwvX+AhfpaTJOQRGdWky4Cb6Gqubn3pgSDeApdcccu+WMrAX1ozzx3H/kEzIHn2NT3mFDUHNkEnOlx7OFgNZY+Wvypz18GCYyW/QIsNi/dk3HTzAymU6rVhGSi9v9OkogASRrSn6OMnFofa+WIwvnHJzHCZ8uY37NSERHv+FcT7CGoHHgU5+3hjEAWsXkX4TRDfrWvzsb/tkDaJbNv0KHo+WjcPHL/rBVIoexZpahaf4z4f1g6DfH6LOOgvwbjJZ3JEHNvE+DM5bY58Asn8MxOayYJ3hZ39J0hdepO+2+YUdkqPPxyJIvufXeoaGtsu0AYQ=='
        );

        $response = $this->swedbank->handleResponse($responseData);

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }
}