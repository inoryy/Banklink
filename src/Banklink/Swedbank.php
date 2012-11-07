<?php

namespace Banklink;

use Banklink\Protocol\iPizza;

/**
 * Banklink implementation for Swedbank using iPizza protocol for communication
 * For specs see https://www.swedbank.ee/static/pdf/business/d2d/paymentcollection/info_banklink_techspec_eng.pdf
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  11.01.2012
 */
class Swedbank extends Banklink
{
    const ENCODING_FIELD = 'VK_ENCODING';

    protected $requestUrl = 'https://www.swedbank.ee/banklink';
    protected $testRequestUrl = 'https://pangalink.net/banklink/008/swedbank';

    /**
     * Force iPizza protocol
     *
     * @param \Banklink\iPizza $protocol
     * @param boolean          $testMode
     * @param string | null    $requestUrl
     */
    public function __construct(iPizza $protocol, $testMode = false, $requestUrl = null)
    {
        parent::__construct($protocol, $testMode, $requestUrl);
    }

    /**
     * @param array $responseData
     *
     * @return string
     */
    protected function getResponseEncoding(array $responseData)
    {
        if (isset($responseData[self::ENCODING_FIELD])) {
            return $responseData[self::ENCODING_FIELD];
        }

        return $this->responseEncoding;
    }

    /**
     * Force UTF-8 encoding
     *
     * @see Banklink::getAdditionalFields()
     *
     * @return array
     */
    protected function getAdditionalFields()
    {
        return array(
            self::ENCODING_FIELD => $this->requestEncoding
        );
    }
}