<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Database;

class Info extends \PleskX\Api\Struct
{

    /** @var integer */
    public $id;
    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id'
        ]);
    }
}
