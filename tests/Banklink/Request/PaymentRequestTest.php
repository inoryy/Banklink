<?php

namespace Banklink\Protocol;

use Banklink\Request\PaymentRequest;
use PHPUnit\Framework\TestCase;

/**
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * @since  05.03.2018
 */
class PaymentRequestTest extends TestCase
{
    public function testShouldBuildRequestHtml() {
        $array = array('KEY' => 'value');
        $expected = '<input id="key" name="KEY" value="value" type="hidden" />';
        $request = new PaymentRequest('', $array);
        $actual = $request->buildRequestHtml();
        $this->assertEquals($expected, $actual);
    }
}