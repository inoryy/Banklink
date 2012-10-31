<?php

namespace Inori\Banklink\Response;

/**
 * Generic response representation
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
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
    const STATUS_FAILURE = 0;
    /**
     * Response signature could not be verified
     */
    const STATUS_ERROR   = -1;

    protected $id;

    protected $date;

    protected $status;

    protected $rawResponseData = array();

    /**
     * Response constructor
     */
    public function __construct($id, $status, \DateTime $date, array $rawResponseData)
    {
        $this->id     = $id;
        $this->status = $status;
        $this->date   = $date;

        $this->rawResponseData = $rawResponseData;
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
     * @return integer
     */
    public function getTransactionId()
    {
        return $this->id;
    }

    /**
     *
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