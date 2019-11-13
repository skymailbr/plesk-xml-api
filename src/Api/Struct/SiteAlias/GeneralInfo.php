<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\SiteAlias;

class GeneralInfo extends \PleskX\Api\Struct
{

    /** @var \PleskX\Api\Struct\SiteAlias\Preferences */
    public $preferences;

    /** @var integer * */
    public $siteId;

    /** @var string * */
    public $name;

    /** @var integer * */
    public $id;

    /** @var string * */
    public $asciiName;

    /**
     * GeneralInfo constructor.
     * @param $apiResponse
     * @throws \Exception
     */
    public function __construct($apiResponse)
    {
        $this->id = $apiResponse->id;
        $this->_initScalarProperties($apiResponse->info, [
            ['pref' => 'preferences'],
            ['site-id' => 'siteId'],
            'name',
            ['ascii-name' => 'asciiName']
        ]);
    }
}