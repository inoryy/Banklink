<?php

namespace Banklink;

use Banklink\Protocol\iPizza;
use Banklink\Response\PaymentResponse;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  31.10.2012
 */
class DanskeBankTest extends \PHPUnit_Framework_TestCase
{
    private $bank;

    public function setUp()
    {
        $protocol = new iPizza(
            'uid258629',
            'Test Testov',
            '119933113300',
            __DIR__.'/data/iPizza/private_key.pem',
            __DIR__.'/data/iPizza/public_key.pem',
            'http://www.google.com',
            true
        );

        $this->bank = new DanskeBank($protocol);
    }

    public function testHandlePaymentResponseSuccessWithSpecialCharacters()
    {
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
            'VK_T_DATETIME' => '2016-06-22T19:36:25+0300',
            'VK_MAC'      => 'WqwQNSpN0vjbmhT9pUghyJUZEBvyN3aBWfNPy7INvKYPifj/SVR6GlLveZk/70dUIPeS6jFCcFEwo8/1y+3TVIwVHQrsP0/L6fZqELuT10M+x/0tjdRd2o8eqiTEd6AlxCAmt306BcZf/FiXLFg+x0TIUb43cgmDb5NbWcMJHDj+U8ivUjNKdpoeF94Fahq5r0X5DTebK6ghMsleZ/70+Hyu7ZBfA3Gor8Wb7U/+C+kcQFUKKbV9N4Drb2PsH4qI2cs2PjKSvrK13CZ7fFRUTl3ka2DM1eClcTxL5HiaOL4/gUi5evqJtkR94foLZlLZ4TMa+PhBIFVR3zsT6eoEFw=='
        );

        $response = $this->bank->handleResponse($responseData);

        $this->assertInstanceOf('Banklink\Response\Response', $response);
        $this->assertEquals(PaymentResponse::STATUS_SUCCESS, $response->getStatus());
    }
}