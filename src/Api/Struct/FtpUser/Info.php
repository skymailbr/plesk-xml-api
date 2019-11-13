<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\FtpUser;

class Info extends \PleskX\Api\Struct
{

    /** @var integer */
    public $id;

    /**
     * Info constructor.
     * @param $apiResponse
     * @throws \Exception
     */
    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'id'
        ]);
    }
}
