<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class Perfomance extends \PleskX\Api\Struct
{
    /** @var integer */
    public $bandwidth;
    /** @var integer */
    public $maxConnections;
    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'bandwidth',
            'max_connections',
        ]);
    }
}
