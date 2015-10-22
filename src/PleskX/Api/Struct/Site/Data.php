<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Site;

class Data extends \PleskX\Api\Struct
{

    /** @var integer **/
    public $id;

    /** @var PleskX\Api\Struct\Site\GeneralInfo **/
    public $genInfo;

    /** @var PleskX\Api\Struct\Site\Hosting **/
    public $hosting;

    /** @var PleskX\Api\Struct\Site\Stat **/
    public $stat;

    /** @var PleskX\Api\Struct\Site\Preference **/
    public $prefs;

    /** @var PleskX\Api\Struct\Site\DiskUsage **/
    public $diskUsage;

    public function __construct($apiResponse)
    {
        $data = $apiResponse->data;
        $data->addChild('id',$apiResponse->id);
        $this->_initScalarProperties($data, [
            'id',
            'gen_info',
            'hosting',
            'stat',
            'prefs',
            'disk_usage',
        ]);
    }
}