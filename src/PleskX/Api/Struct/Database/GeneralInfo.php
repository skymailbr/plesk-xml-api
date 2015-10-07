<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Database;

class GeneralInfo extends \PleskX\Api\Struct
{

    /** @var integer */
    public $id;

    /** @var string */
    public $name;

    /** @var string */
    public $type;

    /** @var integer */
    public $dbServerId;

    /** @var integer */
    public $defaultUserId;

    /** @var integer */
    public $webspaceId;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id',
            'name',
            'type',
            ['webspace-id' => 'webspaceId'],
            ['default-user-id' => 'defaultUserId'],
            ['db-server-id' => 'dbServerId'],
        ]);
    }
}