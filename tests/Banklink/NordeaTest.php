<?php

namespace Banklink;

use Banklink\Nordea;
use Banklink\Protocol\Solo;

/**
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  01.12.2012
 */
class NordeaTest extends \PHPUnit_Framework_TestCase
{
    private $nordea;

    public function setUp()
    {
        $protocol = new Solo(
            '10274577',
            'iC1pmFo2WkrH5bw2WXTzE5JhAaWCpDbi',
            'http://www.google.com',
            'Banklink Nordea',
            '11234532352',
            'sha256'
        );

        $this->nordea = new Nordea($protocol);
    }

    public function testPreparePaymentRequest()
    {
        $expectedRequestData = array(
            'SOLOPMT_VERSION'  => '0003',
            'SOLOPMT_STAMP'    => 1,
            'SOLOPMT_RCV_ID'   => '10274577',
            'SOLOPMT_LANGUAGE' => 3,
            'SOLOPMT_AMOUNT'   => '100',
            'SOLOPMT_REF'      => 13,
            'SOLOPMT_DATE'     => 'EXPRESS',
            'SOLOPMT_MSG'      => 'Test payment',
            'SOLOPMT_RETURN'   => 'http://www.google.com',
            'SOLOPMT_CANCEL'   => 'http://www.google.com',
            'SOLOPMT_REJECT'   => 'http://www.google.com',
            'SOLOPMT_MAC'      => '34AFCF95C1D13078B71E2ADAB9D4D0C9701398B3233294C6903F5B2ABF802011',
            'SOLOPMT_CONFIRM'  => 'YES',
            'SOLOPMT_KEYVERS'  => '0001',
            'SOLOPMT_CUR'      => 'EUR',
            'SOLOPMT_RCV_NAME' => 'Banklink Nordea',
            'SOLOPMT_RCV_ACCOUNT' => '11234532352'
        );

        $request = $this->nordea->preparePaymentRequest(1, 100, 'Test payment', 'ENG', 'EUR');

        $this->assertEquals($expectedRequestData, $request->getRequestData());
        $this->assertEquals('https://netbank.nordea.com/pnbepay/epayn.jsp', $request->getRequestUrl());
    }
}