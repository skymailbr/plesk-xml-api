<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\ServicePlan;

class Data extends \PleskX\Api\Struct
{

    /** @var string */
    public $id;
    /** @var string */
    public $name;
    /** @var PleskX\Api\Struct\ServicePlan\Limits * */
    public $limits;
    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'name',
            'limits',
        ]);
    }
}
