<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Ip;

class Info extends \PleskX\Api\Struct
{
    /** @var string */
    public $ipAddress;

    /** @var string */
    public $netmask;

    /** @var string */
    public $type;

    /** @var string */
    public $interface;

    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'ip_address',
            'netmask',
            'type',
            'interface',
        ]);
    }
}
