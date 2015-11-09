<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\DatabaseServer;

class GeneralInfo extends \PleskX\Api\Struct
{

    /** @var integer */
    public $id;

    /** @var string */
    public $host;

    /** @var string */
    public $type;

    /** @var string */
    public $port;

    public function __construct($apiResponse)
    {
        $this->id = (integer) $apiResponse->id;
        $this->_initScalarProperties($apiResponse->data, [
            'host',
            'port',
            'type'
        ]);
    }
}