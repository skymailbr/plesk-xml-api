<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Customer;

class GeneralInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $personalName;

    /** @var string */
    public $login;

    /** @var integer */
    public $id;

    public function __construct($apiResponse)
    {
        $this->id = (int)$apiResponse->id;
        $this->initScalarProperties($apiResponse->data->gen_info, [
            ['pname' => 'personalName'],
            'login'
        ]);
    }
}
