<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Customer;

class Domain extends \PleskX\Api\Struct
{

    /**  @var integer */
    public $id;
    /**  @var string */
    public $name;
    /**  @var string */
    public $asciiName;
    /**  @var string */
    public $type;
    /**  @var boolean */
    public $main;
    /**  @var string */
    public $guid;
    /**  @var string */
    public $externalId;
    /**  @var string */
    public $parentId;
    /**  @var string */
    public $domainId;
    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'id',
            'name',
            ['ascii-name' => 'asciiName'],
            'type',
            'main',
            'guid',
            ['external-id' => 'externalId'],
            ['parent-id' => 'parentId'],
            ['domain-id' => 'domainId'],
        ]);
    }
}
