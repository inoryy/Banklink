<?php

namespace Banklink\Protocol;

use Banklink\Protocol\Solo;
use Banklink\Response\PaymentResponse;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  25.11.2012
 */
class SoloTest extends \PHPUnit_Framework_TestCase
{
    private $solo;

    public function setUp()
    {
        $this->solo = new Solo(
            '10274768',
            'Test Testov',
            '119933113311',
            'vtWHT6T6z3G0xJlu5oB8ulB85iKhXQIO',
            'http://www.google.com',
            'md5'
        );
    }

    public function testPreparePaymentRequest()
    {
        $expectedRequestData = array(
            'SOLOPMT_VERSION' => '0003',
            'SOLOPMT_STAMP' => 12345,
            'SOLOPMT_RCV_ID' => '10274580',
            'SOLOPMT_LANGUAGE' => 4,
            'SOLOPMT_AMOUNT' => '100',
            'SOLOPMT_REF' => 13,
            'SOLOPMT_DATE' => 'EXPRESS',
            'SOLOPMT_MSG' => 'Test payment',
            'SOLOPMT_RETURN' => 'http://www.google.com',
            'SOLOPMT_CANCEL' => 'http://www.google.com',
            'SOLOPMT_REJECT' => 'http://www.google.com',
            'SOLOPMT_MAC' => '199B150C51085934DAB6072CFF5F7F2C',
            'SOLOPMT_CONFIRM' => 'YES',
            'SOLOPMT_KEYVERS' => '0001',
            'SOLOPMT_CUR' => 'EUR',
        );

        $request = $this->solo->preparePaymentRequestData(1, 100, 'Test payment', 'UTF-8', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request);
    }
}