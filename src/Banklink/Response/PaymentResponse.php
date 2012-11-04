<?php

namespace Banklink\Response;

/**
 * Payment response representation
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  31.10.2012
 */
class PaymentResponse extends Response
{
    protected $sum;
    protected $orderId;
    protected $currency;
    protected $senderName;
    protected $senderAccountNumber;

    /**
     * Set orderId
     *
     * @param integer $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Get orderId
     *
     * @return integer
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set sum
     *
     * @param float $sum
     */
    public function setSum($sum)
    {
        $this->sum = $sum;
    }

    /**
     * Get sum
     *
     * @return float
     */
    public function getSum()
    {
        return $this->sum;
    }

    /**
     * Set currency
     *
     * @param string $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Get currency
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set senderName
     *
     * @param string $senderName
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * Get senderName
     *
     * @return string
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * Set senderBankAccount
     *
     * @param integer $senderBankAccount
     */
    public function setSenderBankAccount($senderBankAccount)
    {
        $this->senderBankAccount = $senderBankAccount;
    }

    /**
     * Get senderBankAccount
     *
     * @return integer
     */
    public function getSenderBankAccount()
    {
        return $this->senderBankAccount;
    }
}