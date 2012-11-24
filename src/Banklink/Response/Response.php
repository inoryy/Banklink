<?php

namespace Banklink\Response;

/**
 * Generic response representation
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  31.10.2012
 */
class Response
{
    /**
     * Response signature verified & transaction was succesful
     */
    const STATUS_SUCCESS = 1;
    /**
     * Response signature verified, but transaction was canceled
     */
    const STATUS_CANCEL = 0;
    /**
     * Response signature could not be verified
     */
    const STATUS_ERROR = -1;

    protected $id;

    protected $date;

    protected $status;

    protected $rawResponseData = array();

    /**
     *
     * @param integer $status
     * @param array $rawResponseData
     */
    public function __construct($status, array $rawResponseData)
    {
        $this->status = $status;
        $this->rawResponseData = $rawResponseData;
    }

    /**
     *
     * @return boolean
     */
    public function isSuccesful()
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     *
     * @param string $id
     */
    public function setTransactionId($id)
    {
        $this->id = $id;
    }

    /**
     *
     * @return integer
     */
    public function getTransactionId()
    {
        return $this->id;
    }

    /**
     * @param \DateTime $date
     */
    public function setTransactionDate($date)
    {
        $this->date = $date;
    }

    /**
     * @return \DateTime
     */
    public function getTransactionDate()
    {
        return $this->date;
    }

    /**
     * Get raw response data
     *
     * @return array
     */
    public function getRawResponseData()
    {
        return $this->rawResponseData;
    }
}