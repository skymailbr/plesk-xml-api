<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\ServicePlan;

class Info extends \PleskX\Api\Struct
{
    /** @var string */
    public $id;
    /** @var string */
    public $name;
    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'name'
        ]);
    }
}
