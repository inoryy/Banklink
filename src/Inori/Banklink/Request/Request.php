<?php

namespace Inori\Banklink\Request;

/**
 * Generic request representation
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  31.10.2012
 */
abstract class Request
{
    protected $requestUrl;
    protected $requestData = array();

    /**
     * @param string $requestUrl
     * @param array  $requestData
     */
    public function __construct($requestUrl, array $requestData)
    {
        $this->requestUrl  = $requestUrl;
        $this->requestData = $requestData;
    }

    /**
     * Get request data which should be sent to desired bank
     *
     * @return array
     */
    public function getRequestData()
    {
        return $this->requestData;
    }

    /**
     * Get URL to where request data should be sent (most likely via POST method)
     *
     * @return string
     */
    public function getRequestUrl()
    {
        return $this->requestUrl;
    }
}