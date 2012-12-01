<?php

namespace Banklink\Protocol\Solo\Response;

use Banklink\Response\PaymentResponse as BasePaymentResponse;

/**
 * Payment response representation for Solo protocol
 *
 * NB! Solo payment response has much less data than other protocols, you must manually set it to use in places like view
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  01.12.2012
 */
class PaymentResponse extends BasePaymentResponse
{
    protected $paymentCode;

    /**
     * Set paymentCode
     *
     * @param string $paymentCode
     */
    public function setPaymentCode($paymentCode)
    {
        $this->paymentCode = $paymentCode;
    }

    /**
     * Get paymentCode
     *
     * @return string
     */
    public function getPaymentCode()
    {
        return $this->paymentCode;
    }
}