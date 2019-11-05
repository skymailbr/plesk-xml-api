<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Server\Statistics;

class Version extends \PleskX\Api\Struct
{

    /** @var string */
    public $internalName;

    /** @var string */
    public $version;

    /** @var string */
    public $pleskOs;

    /** @var string */
    public $pleskOsVersion;

    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            ['plesk_name' => 'internalName'],
            ['plesk_version' => 'version'],
            ['plesk_os' => 'pleskOs'],
            ['plesk_os_version' => 'pleskOsVersion'],
        ]);
    }
}
