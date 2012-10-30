<?php

namespace Inori\Test\Banklink\iPizza;

use Inori\Banklink\iPizza\iPizza;

/**
 * iPizza protocol test
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  15.01.2012
 */
class iPizzaTest extends \PHPUnit_Framework_TestCase
{
    private $iPizza;

    public function setUp()
    {
        $this->iPizza = new iPizza(
            'uid200486',
            'Test Testov',
            '1234567890',
            __DIR__.'/Fixture/private_key.pem',
            __DIR__.'/Fixture/public_key.pem',
            'http://www.google.com',
            'http://www.google.com'
        );
    }

    public function testPreparePaymentRequest()
    {
        $expected = array(
          'VK_SERVICE' => '1001',
          'VK_VERSION' => '008',
          'VK_SND_ID'  => 'uid200486',
          'VK_STAMP'   => '1',
          'VK_AMOUNT'  => '100',
          'VK_CURR'    => 'EUR',
          'VK_ACC'     => '1234567890',
          'VK_NAME'    => 'Test Testov',
          'VK_REF'     => '13',
          'VK_MSG'     => 'Test payment',
          'VK_CHARSET' => 'UTF-8',
          'VK_ENCODING'=> 'UTF-8',
          'VK_RETURN'  => 'http://www.google.com',
          'VK_CANCEL'  => 'http://www.google.com',
          'VK_LANG'    => 'ENG',
          'VK_MAC'     => 'fltl/Nnqbi3YGZwe1bY9hC23Kj7fImqm0CqQbpRKleJXfW+/tF+s5qRl+Tlz0FIpTSYFVHBMwLOXNVzG0JUVHJRyZeTkoAovot7ffLL1plycfPizgcATdZBzN5qsM+qFIbXXzvmoNGGBDGo0jFU7LCJBdq/JLM5JWNcVLVO6r67skkH/6k2cFoaAF0b00nfDFaKytJVivRcBqie1H0G+fAXNLJCCm+lfrr0bSG1duGwDuzpXONpyl2zUxjDJXXQurIEM8ffilvJDZC81VIe1Q/VHNVflumquXn5ETjrHQPmqSvFloUMh2gwi8wtuOyfhMfsS99B+B8yx4rfuUR+Kiw=='
        );

        $request = $this->iPizza->preparePaymentRequest('1', 'Test payment', '100', 'ENG', 'EUR');

        $this->assertEquals($expected, $request);
    }

    public function testVerifyPaymentResponseCorrect()
    {
        $response = array(
            'VK_SERVICE'  => '1101',
            'VK_VERSION'  => '008',
            'VK_SND_ID'   => 'HP',
            'VK_REC_ID'   => 'uid200486',
            'VK_STAMP'    => '1',
            'VK_T_NO'     => '17944',
            'VK_AMOUNT'   => '100',
            'VK_CURR'     => 'EUR',
            'VK_REC_ACC'  => '1234567890',
            'VK_REC_NAME' => 'Test Testov',
            'VK_REF'      => '13',
            'VK_MSG'      => 'Test payment',
            'VK_T_DATE'   => '30.10.2012',
            'VK_ENCODING' => 'UTF-8',
            'VK_AUTO'     => 'N',
            'VK_SND_NAME' => 'Test Account Owner',
            'VK_SND_ACC'  => '221234576897',
            'VK_MAC'      => '0Rcj4zimJpLmnqoFPeAChALa/QuA6OD5J/GJAudNU8iDxGylN7FHoC0RPeOuAo8E6P5wAM4zOaFwW7Fd4q0APSb8xBJbmztf1uxQXPWuD9aR2BGO06KL+HLrZ8yM+vh4941NA3ybg3EIoZzcd5Qqfk0BMopLy6RHC70rnwI1wceOn9k7+ar79MNFQU8Q38KeOdczqRZx/svlTLaUvPmBQNvJj9GXRVKTWFEBmnzJ+NEM0OAGoCbUaeZDoSJN1OvetloHpYt2McchT5XPPKinO/VpKr/WMi7UorfT+cbmjuCxgji/R0UJO1ZCWzUKO2D572ULZqG7J7FIvXzI9R/Ydw=='
        );

        $this->assertTrue($this->iPizza->verifyPaymentResponse($response));
    }
}