<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\PHPHandler;

class Info extends \PleskX\Api\Struct
{
    /** @var string */
    public $id;
    /** @var string */
    public $displayName;
    /** @var string */
    public $fullVersion;
    /** @var string */
    public $version;
    /** @var string */
    public $type;
    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'display-name',
            'full-version',
            'version',
            'type',
        ]);
    }
}
