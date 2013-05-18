<?php

namespace Banklink\Protocol;

use Banklink\Protocol\iPizza;
use Banklink\Response\PaymentResponse;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
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
            __DIR__.'/../data/iPizza/private_key.pem',
            __DIR__.'/../data/iPizza/public_key.pem',
            'http://www.google.com'
        );
    }

    public function testPreparePaymentRequest()
    {
        $expectedRequestData = array(
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
          'VK_RETURN'  => 'http://www.google.com',
          'VK_CANCEL'  => 'http://www.google.com',
          'VK_LANG'    => 'ENG',
          'VK_MAC'     => 'g4SMbCZEbxSXF7qx8ggcRHTyWOx4Dqkb0eM6atoEC5A12SAlWDgIw5TnB319KtreUcEubrjZz9z4NQgVrSieoOX9yr3G7ciLopGaoajAr6RA9RTYP0QDoArTuDKBqFwRT6D+erTggu9Dz3G/dQKlL9SCQtUxV6yCHp0cLgzYmtUGXoC7x4WnP1NuJZwlBnJI3acsCNyw5gTnEHle0Xd2OElH84aKlItqSsPbFirWhZRLfLy8uyiwSseChnTnDXCINyFLypHNTvvn+DaE8m+nyDkL4Jt3L2rciYkLPuoXSY3JGXTzjS7TkpOPUEtBQZ65ZylltduAeknxocvSZYUskA=='
        );

        $request = $this->iPizza->preparePaymentRequestData(1, 100, 'Test payment', 'UTF-8', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request);
    }

    public function testHandlePaymentResponseSuccess()
    {
        $responseData = array(
            'VK_SERVICE'  => '1101',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'GENIPIZZA',
            'VK_REC_ID'   => 'uid258629',
            'VK_STAMP'    => '1',
            'VK_T_NO'     => '17947',
            'VK_AMOUNT'   => '100',
            'VK_CURR'     => 'EUR',
            'VK_REC_ACC'  => '119933113300',
            'VK_REC_NAME' => 'Test Testov',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_T_DATE'   => '31.10.2012',
            'VK_AUTO'     => 'N',
            'VK_SND_NAME' => 'Test Account Owner',
            'VK_SND_ACC'  => '221234576897',
            'VK_MAC'      => 'Lma6+YAm7JyU0WOOMpqNINT7ub8xLjrmYePBRcAFrY/Ea8Z/EhM9rYFMQive5GLDagWvay8zCNIHevYUD0P7I49hZwivluRF8C+cLPUaOcH8ySp5vHscgqurS7Aqg+gNWrRKwqWTjuxvjuqD8r/JlY1N+3sDpF1mU8HAc7NnRGDOyo1AmwUyOPa7mLsAYPXuzKW+qXqGL5uGMOqAw9kRgNkxCQHh/QpmvX7jm0oQ7KxypIAIZAYBjf8usDp3OT4AKd9B/FJ5fdX7JOSlL+Kjj7uD3qW3kVBz1JJ/riVRGdct5qouTNe0deB2jZbD5fuWa1XlJVWOG2xOGfGYhN7pfg=='
        );

        $response = $this->iPizza->handleResponse($responseData, 'ISO-8859-1');

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }

    public function testHandlePaymentResponseCancel()
    {
        $responseData = array(
            'VK_SERVICE'  => '1901',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'GENIPIZZA',
            'VK_REC_ID'   => 'uid258629',
            'VK_STAMP'    => '1',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_AUTO'     => 'N',
            'VK_MAC'      => 'bg8rRUxE6W+RhkdJyUADQl43soI7C6ohtkwGDRCXyeRDQk5B2D1kkmuzJ6lZopttAFMnU1C6MOynF/VWXFVX5YZmpnm9vpFy6uz9uH/bjMfRddj0pkWe6Afa3l2MET+Nk7xOxxxHlJBX3NZndp3xO7Wdi4pyx4kZjpcM6lR+Dq9mhh0N+45bDyh+IkEmEC3GrGwQTbFGYSG9gh2zv4BuFgQj/lSprf6qUyQf8wmr/onSOGwuenYFFxYOG6aUU+/5ha0TLyQg8ed2SOAylAbSKEN+Ud2xEZ8WzxEwfiYf9WBiooRYyydmS2vRZV2KGCfUqgoPzl7b5NaSPW2PW7CheQ=='
        );

        $response = $this->iPizza->handleResponse($responseData, 'ISO-8859-1');

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_CANCEL, $response->getStatus());
    }

    public function testHandlePaymentResponseError()
    {
        $responseData = array(
            'VK_SERVICE'  => '1101',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'GENIPIZZA',
            'VK_REC_ID'   => 'uid258629',
            'VK_STAMP'    => '2',
            'VK_T_NO'     => '17947',
            'VK_AMOUNT'   => '100',
            'VK_CURR'     => 'EUR',
            'VK_REC_ACC'  => '119933113300',
            'VK_REC_NAME' => 'Test Testov',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_T_DATE'   => '31.10.2012',
            'VK_AUTO'     => 'N',
            'VK_SND_NAME' => 'Test Account Owner',
            'VK_SND_ACC'  => '221234576897',
            'VK_MAC'      => 'Lma6+YAm7JyU0WOOMpqNINT7ub8xLjrmYePBRcAFrY/Ea8Z/EhM9rYFMQive5GLDagWvay8zCNIHevYUD0P7I49hZwivluRF8C+cLPUaOcH8ySp5vHscgqurS7Aqg+gNWrRKwqWTjuxvjuqD8r/JlY1N+3sDpF1mU8HAc7NnRGDOyo1AmwUyOPa7mLsAYPXuzKW+qXqGL5uGMOqAw9kRgNkxCQHh/QpmvX7jm0oQ7KxypIAIZAYBjf8usDp3OT4AKd9B/FJ5fdX7JOSlL+Kjj7uD3qW3kVBz1JJ/riVRGdct5qouTNe0deB2jZbD5fuWa1XlJVWOG2xOGfGYhN7pfg=='
        );

        $response = $this->iPizza->handleResponse($responseData, 'ISO-8859-1');

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_ERROR, $response->getStatus());
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testHandleResponseUnsupportedService()
    {
        $responseData = array(
            'VK_SERVICE'  => '1111',
        );

        $response = $this->iPizza->handleResponse($responseData, 'ISO-8859-1');
    }
}