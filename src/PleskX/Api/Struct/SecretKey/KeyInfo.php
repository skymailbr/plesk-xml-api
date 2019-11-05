<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\SecretKey;

class KeyInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $key;

    /** @var string */
    public $ipAddress;

    /** @var string */
    public $description;

    /** @var string */
    public $login;

    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'key',
            'ip_address',
            'description',
            'login',
        ]);
    }
}
