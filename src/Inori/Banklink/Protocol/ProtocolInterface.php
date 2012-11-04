<?php

namespace Inori\Banklink\Protocol;

/**
 * Generic Protocol interface
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  11.01.2012
 */
interface ProtocolInterface
{
    /**
     * initialize basic data that will be used for all issued service requests
     *
     * @param string  $sellerId
     * @param string  $sellerName
     * @param integer $sellerAccNum
     * @param string  $privateKey    Private key location
     * @param string  $publicKey     Public key (certificate) location
     * @param string  $endpointUrl
     * @param string  $version
     */
    function __construct($sellerId, $sellerName, $sellerAccNum, $privateKey, $publicKey, $endpointUrl, $version = '008');

    /**
     * @param type $orderId
     * @param type $sum
     * @param type $message
     * @param type $language
     * @param type $currency
     *
     * @return array
     */
    function preparePaymentRequestData($orderId, $sum, $message = '', $language = 'EST', $currency = 'EUR');

    /**
     * Determine which response exactly by service id, if it's supported then call related internal method
     *
     * @param array $responseData
     *
     * @return \Inori\Banklink\Response\Response
     */
    function handleResponse(array $responseData);
}