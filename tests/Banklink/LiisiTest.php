<?php

namespace Banklink;

use Banklink\SEB;
use Banklink\Protocol\iPizza;

/**
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * @since  19.07.2016
 */
class LiisiTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Banklink
     */
    private $liisi;

    public function setUp()
    {
        $protocol = new iPizza(
            'aalux_makselink',
            '',
            '',
            __DIR__.'/data/iPizza/liisi-test.pem',
            __DIR__.'/data/iPizza/test-ipizza-2016.pub',
            'http://www.google.com'
        );

        $this->liisi = new Liisi($protocol);
    }

    public function testPreparePaymentRequest()
    {
        $now = new \DateTime();
        $expectedRequestData = array(
          'VK_SERVICE'  => '1011',
          'VK_VERSION'  => '008',
          'VK_SND_ID'   => 'aalux_makselink', //provided by Liisi: Merchant Username
          'VK_STAMP'    => '1',
          'VK_AMOUNT'   => '100',
          'VK_CURR'     => 'EUR',
          'VK_REF'      => '13',
          'VK_MSG'      => 'Test payment',
          'VK_RETURN'   => 'http://www.google.com',
          'VK_CANCEL'   => 'http://www.google.com',
          'VK_DATETIME' => $now->format(\DateTime::ISO8601),
          'VK_MAC'      => 'generated mac',
          'VK_ENCODING' => 'UTF-8',
          'VK_LANG'     => 'ENG',
          'VK_ACC' => '',
          'VK_NAME' => ''
        );

        $request = $this->liisi->preparePaymentRequest(1, 100, 'Test payment', 'ENG', 'EUR');
        $actualRequest = $request->getRequestData();
        $actualRequest['VK_MAC'] = 'generated mac';
        $this->assertEquals($expectedRequestData, $actualRequest);
        $this->assertEquals('https://klient.liisi.ee/api/ipizza/', $request->getRequestUrl());
    }


    public function testHandlePaymentResponseSuccess()
    {
        $now = new \DateTime();
        $responseData = array(
            'VK_SERVICE'  => '1111',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'test.liisi.ee',
            'VK_REC_ID'   => 'aalux_makselink',//provided by Liisi: Merchant Username
            'VK_STAMP'    => '1',
            'VK_T_NO'     => '17947',
            'VK_AMOUNT'   => '100',
            'VK_CURR'     => 'EUR',
            'VK_REC_ACC'  => '119933113300',
            'VK_REC_NAME' => 'Test Testov',
            'VK_SND_ACC'  => '',
            'VK_SND_NAME' => 'Test Account Owner',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_T_DATETIME'=> $now->format(\DateTime::ISO8601),
            //käivita vastavate andmetega testkeskkonna pihta ja loe sealt mac, lisaks fikseeri kuupäev
            'VK_MAC'      => 'Lma6+YAm7JyU0WOOMpqNINT7ub8xLjrmYePBRcAFrY/Ea8Z/EhM9rYFMQive5GLDagWvay8zCNIHevYUD0P7I49hZwivluRF8C+cLPUaOcH8ySp5vHscgqurS7Aqg+gNWrRKwqWTjuxvjuqD8r/JlY1N+3sDpF1mU8HAc7NnRGDOyo1AmwUyOPa7mLsAYPXuzKW+qXqGL5uGMOqAw9kRgNkxCQHh/QpmvX7jm0oQ7KxypIAIZAYBjf8usDp3OT4AKd9B/FJ5fdX7JOSlL+Kjj7uD3qW3kVBz1JJ/riVRGdct5qouTNe0deB2jZbD5fuWa1XlJVWOG2xOGfGYhN7pfg==',
            'VK_ENCODING' => 'UTF-8',
            'VK_LANG'     => 'ENG',
            'VK_AUTO'     => 'N'
        );

        $response = $this->liisi->handleResponse($responseData);

        $this->assertInstanceOf('\Banklink\Response\Response', $response);
        $this->assertEquals(\Banklink\Response\PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }
}