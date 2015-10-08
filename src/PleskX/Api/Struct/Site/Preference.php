<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Site;

class Preference extends \PleskX\Api\Struct
{
    /** @var boolean */
    public $www;

    /** @var string */
    public $statTtl;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'www',
            'stat_ttl',
        ]);
    }
}