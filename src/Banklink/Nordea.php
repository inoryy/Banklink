<?php

namespace Banklink;

use Banklink\Protocol\Solo;

/**
 * Banklink implementation for Nordea using Solo protocol for communication
 * For specs see http://www.nordea.ee/sitemod/upload/root/www.nordea.ee%20-%20default/Teenused%20firmale/E-Payment_v1_1.pdf
 *
 * @author Roman Marintsenko <inoryy@gmail.com>
 * @author Markus Karileet <markus.karileet@codehouse.ee>
 * @since  25.11.2012
 */
class Nordea extends Banklink
{
    protected $productionRequestUrl = 'https://netbank.nordea.com/pnbepay/epayn.jsp';
    protected $testRequestUrl = 'https://pangalink.net/banklink/nordea';

    protected $requestEncoding = 'ISO-8859-1';

    /**
     * Force Solo protocol
     *
     * @param \Banklink\Protocol\Solo $protocol
     * @param boolean                 $testMode
     */
    public function __construct(Solo $protocol, $testMode = false)
    {
        parent::__construct($protocol, $testMode);
    }

    protected function getAdditionalFields()
    {
        return array();
    }
}