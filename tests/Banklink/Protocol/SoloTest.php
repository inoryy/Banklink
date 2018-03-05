<?php

namespace Banklink\Protocol;

use Banklink\Response\PaymentResponse;
use PHPUnit\Framework\TestCase;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  25.11.2012
 */
class SoloTest extends TestCase
{
    private $solo;

    public function setUp()
    {
        $this->solo = new Solo(
            '10274580',
            'WBTGlRKU57bHOnH6Ey0W4TFrsV9PDAiu',
            'http://www.google.com',
            null,
            null,
            'md5'
        );
    }

    public function testPreparePaymentRequest()
    {
        $expectedRequestData = array(
            'SOLOPMT_VERSION' => '0003',
            'SOLOPMT_STAMP' => 1,
            'SOLOPMT_RCV_ID' => '10274580',
            'SOLOPMT_LANGUAGE' => 3,
            'SOLOPMT_AMOUNT' => '100',
            'SOLOPMT_REF' => 13,
            'SOLOPMT_DATE' => 'EXPRESS',
            'SOLOPMT_MSG' => 'Test payment',
            'SOLOPMT_RETURN' => 'http://www.google.com',
            'SOLOPMT_CANCEL' => 'http://www.google.com',
            'SOLOPMT_REJECT' => 'http://www.google.com',
            'SOLOPMT_MAC' => 'A53DD635463AE22E7827D7C6A5BC133C',
            'SOLOPMT_CONFIRM' => 'YES',
            'SOLOPMT_KEYVERS' => '0001',
            'SOLOPMT_CUR' => 'EUR',
        );

        $request = $this->solo->preparePaymentRequestData(1, 100, 'Test payment', 'ISO-8859-1', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request);
    }

    public function testHandlePaymentResponseSuccess()
    {
        $responseData = array(
            'SOLOPMT_RETURN_VERSION' => '0003',
            'SOLOPMT_RETURN_STAMP'   => '1',
            'SOLOPMT_RETURN_PAID'    => 'PEPM20121128000000019311',
            'SOLOPMT_RETURN_REF'     => '13',
            'SOLOPMT_RETURN_MAC'     => '37D887A50DD8DF11406A1617B5FDE5BF'
        );

        $response = $this->solo->handleResponse($responseData, 'ISO-8859-1');

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }

    public function testHandlePaymentResponseCancel()
    {
        $responseData = array(
            'SOLOPMT_RETURN_VERSION' => '0003',
            'SOLOPMT_RETURN_STAMP'   => '1',
            'SOLOPMT_RETURN_REF'     => '13',
            'SOLOPMT_RETURN_MAC'     => '2A6495F229AD3510EE2DA8DDA7EB0809'
        );

        $response = $this->solo->handleResponse($responseData, 'ISO-8859-1');

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_CANCEL, $response->getStatus());
    }

    public function testHandlePaymentResponseError()
    {
        $responseData = array(
            'SOLOPMT_RETURN_VERSION' => '0003',
            'SOLOPMT_RETURN_STAMP'   => '1',
            'SOLOPMT_RETURN_REF'     => '13',
            'SOLOPMT_RETURN_MAC'     => '2A649522F9AD3510EE2DA8DDA7EB0809'
        );

        $response = $this->solo->handleResponse($responseData, 'ISO-8859-1');

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_ERROR, $response->getStatus());
    }
}