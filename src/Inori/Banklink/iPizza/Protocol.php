<?php

namespace Inori\Banklink\iPizza;

use Inori\Banklink\Banklink;

/**
 * This class implements iPizza protocol support
 *
 * @author Roman Marintsenko <roman.marintsenko@knplabs.com>
 * @since  11.01.2012
 */
abstract class Protocol
{
    protected $privateKeyLocation;
    protected $publicKeyLocation;

    protected $sellerId;
    protected $sellerName;
    protected $sellerAccountNumber;

    protected $protocolVersion = '008';
    protected $charset         = 'UTF-8';


    /**
     * initialize basic data that will be used for all issued service requests
     */
    public function __construct($sellerId, $sellerName, $sellerAccNum, $privKeyLoc, $pubKeyLoc)
    {
        $this->sellerId            = $sellerId;
        $this->sellerName          = $sellerName;
        $this->sellerAccountNumber = $sellerAccNum;
        $this->publicKeyLocation   = $pubKeyLoc;
        $this->privateKeyLocation  = $privKeyLoc;
    }
}