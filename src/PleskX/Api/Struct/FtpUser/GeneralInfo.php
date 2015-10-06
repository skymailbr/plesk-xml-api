<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\FtpUser;

class GeneralInfo extends \PleskX\Api\Struct
{

    /** @var integer */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $home;

    /** @var integer */
    public $quota;

    /** @var integer */
    public $webspaceId;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'home',
            'quota',
            ['webspace-id' => 'webspaceId'],
        ]);
    }
}