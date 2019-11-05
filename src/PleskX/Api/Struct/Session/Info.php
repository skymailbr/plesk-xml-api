<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Session;

class Info extends \PleskX\Api\Struct
{
    /** @var string */
    public $id;

    /** @var string */
    public $type;

    /** @var string */
    public $ipAddress;

    /** @var string */
    public $login;

    /** @var string */
    public $loginTime;

    /** @var string */
    public $idle;

    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'type',
            'ip-address',
            'login',
            'login-time',
            'idle',
        ]);
    }
}
