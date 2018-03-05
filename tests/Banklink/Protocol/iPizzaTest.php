<?php

namespace Banklink\Protocol;

use Banklink\Response\PaymentResponse;
use PHPUnit\Framework\TestCase;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * @since  15.01.2012
 */
class iPizzaTest extends TestCase
{
    /**
     * @var \Banklink\Protocol\iPizza
     */
    private $iPizza;

    public function setUp()
    {
        $this->iPizza = \Mockery::mock('Banklink\Protocol\iPizza');
        $this->iPizza->shouldReceive('getRequestSignature')->andReturn('unit-testing');
        $this->iPizza->makePartial();
        $this->iPizza->configure(
            'uid258629',
            'Test Testov',
            '119933113300',
            __DIR__.'/../data/iPizza/private_key.pem',
            __DIR__.'/../data/iPizza/public_key.pem',
            'http://www.google.com');
    }

    public function testPreparePaymentRequest()
    {
        $now = new \DateTime();
        $expectedRequestData = array(
          'VK_SERVICE' => '1011',
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
          'VK_MAC'     => 'unit-testing',
          'VK_DATETIME' => $now->format(\DateTime::ISO8601)
        );

        $request = $this->iPizza->preparePaymentRequestData(1, 100, 'Test payment', 'UTF-8', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request);
    }

    public function testHandlePaymentResponseSuccess()
    {
        $this->iPizza->shouldReceive('verifyResponseSignature')->andReturn(true);
        $now = new \DateTime();
        $responseData = array(
            'VK_SERVICE'  => '1111',
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
            'VK_MAC'      => 'unit-testing',
            'VK_T_DATETIME' => $now->format(\DateTime::ISO8601)
        );

        $response = $this->iPizza->handleResponse($responseData, 'ISO-8859-1');

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }

    public function testHandlePaymentResponseCancel()
    {
        $this->iPizza->shouldReceive('verifyResponseSignature')->andReturn(true);
        $responseData = array(
            'VK_SERVICE'  => '1911',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'GENIPIZZA',
            'VK_REC_ID'   => 'uid258629',
            'VK_STAMP'    => '1',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_AUTO'     => 'N',
            'VK_MAC'      => 'unit-testing'
        );

        $response = $this->iPizza->handleResponse($responseData, 'ISO-8859-1');

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_CANCEL, $response->getStatus());
    }

    public function testHandlePaymentResponseError()
    {
        $this->iPizza->shouldReceive('verifyResponseSignature')->andReturn(false);
        $now = new \DateTime();
        $responseData = array(
            'VK_SERVICE'  => '1111',
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
            'VK_MAC'      => 'unit-testing',
            'VK_T_DATETIME' => $now->format(\DateTime::ISO8601)
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
            'VK_SERVICE'  => '1101',
        );

        $response = $this->iPizza->handleResponse($responseData, 'ISO-8859-1');
    }
}