<?php

namespace Banklink;

use Banklink\Protocol\Solo;

/**
 * Banklink implementation for Nordea using Solo protocol for communication
 * For specs see http://www.nordea.ee/sitemod/upload/root/www.nordea.ee%20-%20default/Teenused%20firmale/E-Payment_v1_1.pdf
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @since  25.11.2012
 */
class Nordea extends Banklink
{
    protected $requestUrl = 'https://netbank.nordea.com/pnbepay/epayn.jsp';
    protected $testRequestUrl = 'https://pangalink.net/banklink/0003/nordea';

    protected $requestEncoding = 'ISO-8859-1';

    /**
     * Force Solo protocol
     *
     * @param \Banklink\Protocol\Solo $protocol
     * @param boolean                 $testMode
     * @param string | null           $requestUrl
     */
    public function __construct(Solo $protocol, $testMode = false, $requestUrl = null)
    {
        parent::__construct($protocol, $testMode, $requestUrl);
    }

    protected function getAdditionalFields()
    {
        return array();
    }
}