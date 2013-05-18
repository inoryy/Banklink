<?php

namespace Banklink\Request;

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
     * Generates HTML view for request <input> fields
     * NB! This does not generate actual <form> element, it must be rendered manually
     *
     * @return string
     */
    public function buildRequestHtml()
    {
        $output = '';

        foreach ($this->requestData as $key => $value) {
            $output .= sprintf('<input id="%s" name="%s" value="%s" type="hidden" />', strtolower($key), $key, $value);
        }

        return $output;
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