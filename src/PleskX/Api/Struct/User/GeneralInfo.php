<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\User;

class GeneralInfo extends \PleskX\Api\Struct
{

    /** @var string */
    public $login;

    /** @var string */
    public $name;

    /** @var string */
    public $email;

    /** @var string */
    public $ownerGuid;

    /** @var string */
    public $guid;

    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'login',
            'name',
            'email',
            'owner-guid',
            'guid',
        ]);
    }
}
