<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Struct\SiteAlias;

class Info extends \PleskX\Api\Struct
{
    /** @var string */
    public $status;

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
            'id',
            'status'
        ]);
    }
}
