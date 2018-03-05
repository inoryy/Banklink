<?php

namespace Banklink;

use Banklink\Response\PaymentResponse;
use PHPUnit\Framework\TestCase;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * @since  31.10.2012
 */
class DanskeBankTest extends TestCase
{
    /**
     * @var DanskeBank
     */
    private $bank;

    public function setUp()
    {
        $protocol = \Mockery::mock('Banklink\Protocol\iPizza')->makePartial();
        $protocol->shouldReceive('getRequestSignature')->once()->andReturn('unit-testing');
        $protocol->shouldReceive('verifyResponseSignature')->once()->andReturn(true);
        $protocol->configure(
            'uid258629',
            'Test Testov',
            '119933113300',
            __DIR__.'/data/iPizza/private_key.pem',
            __DIR__.'/data/iPizza/public_key.pem',
            'http://www.google.com');
        $this->bank = new DanskeBank($protocol, true);
    }

    public function testHandlePaymentResponseSuccessWithSpecialCharacters()
    {
        $now = new \DateTime();
        $responseData = array(
            'VK_SERVICE'  => '1111',
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
            'VK_T_DATETIME' => $now->format(\DateTime::ISO8601),
            'VK_MAC'      => 'unit-testing'
        );

        $response = $this->bank->handleResponse($responseData);

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }
}