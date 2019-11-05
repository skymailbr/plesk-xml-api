<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Server;

class UpdatesInfo extends \PleskX\Api\Struct
{
    /** @var string */
    public $lastInstalledUpdate;

    /** @var boolean */
    public $installUpdatesAutomatically;

    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'last_installed_update',
            'install_updates_automatically',
        ]);
    }
}
