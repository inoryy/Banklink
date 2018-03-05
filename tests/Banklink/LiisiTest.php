<?php

namespace Banklink;

use PHPUnit\Framework\TestCase;

/**
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * @since  19.07.2016
 */
class LiisiTest extends TestCase
{
    /**
     * @var Liisi
     */
    private $liisi;

    public function setUp()
    {
        $protocol = \Mockery::mock('Banklink\Protocol\iPizza')->makePartial();
        $protocol->shouldReceive('getRequestSignature')->once()->andReturn('unit-testing');
        $protocol->shouldReceive('verifyResponseSignature')->andReturn(true);
        $protocol->configure(
            'makselink',
            '',
            '',
            __DIR__.'/data/iPizza/liisi-test.pem',
            __DIR__.'/data/iPizza/test-ipizza-2016.pub',
            'http://www.google.com');
        $this->liisi = new Liisi($protocol, true);
    }

    public function testPreparePaymentRequest()
    {
        $now = new \DateTime();
        $expectedRequestData = array(
          'VK_SERVICE'  => '1011',
          'VK_VERSION'  => '008',
          'VK_SND_ID'   => 'makselink', //provided by Liisi: Merchant Username
          'VK_STAMP'    => '1',
          'VK_AMOUNT'   => '100',
          'VK_CURR'     => 'EUR',
          'VK_REF'      => '13',
          'VK_MSG'      => 'Test payment',
          'VK_RETURN'   => 'http://www.google.com',
          'VK_CANCEL'   => 'http://www.google.com',
          'VK_DATETIME' => $now->format(\DateTime::ISO8601),
          'VK_MAC'      => 'unit-testing',
          'VK_ENCODING' => 'UTF-8',
          'VK_LANG'     => 'ENG',
          'VK_ACC' => '',
          'VK_NAME' => ''
        );

        $request = $this->liisi->preparePaymentRequest(1, 100, 'Test payment', 'ENG', 'EUR');
        $actualRequest = $request->getRequestData();
        $this->assertEquals($expectedRequestData, $actualRequest);
        $this->assertEquals('https://prelive.liisi.ee:8953/api/ipizza/', $request->getRequestUrl());
    }


    public function testHandlePaymentResponseSuccess()
    {
        $now = new \DateTime();
        $responseData = array(
            'VK_SERVICE'  => '1111',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'test.liisi.ee',
            'VK_REC_ID'   => 'makselink',//provided by Liisi: Merchant Username
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
            'VK_MAC'      => 'unit-testing',
            'VK_ENCODING' => 'UTF-8',
            'VK_LANG'     => 'ENG',
            'VK_AUTO'     => 'N'
        );

        $response = $this->liisi->handleResponse($responseData);

        $this->assertInstanceOf('\Banklink\Response\Response', $response);
        $this->assertEquals(\Banklink\Response\PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }
}