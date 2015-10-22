<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class Data extends \PleskX\Api\Struct
{

    /** @var integer **/
    public $id;

    /** @var PleskX\Api\Struct\Webspace\GeneralInfo **/
    public $genInfo;

    /** @var PleskX\Api\Struct\Webspace\Hosting **/
    public $hosting;

    /** @var PleskX\Api\Struct\Webspace\Limits **/
    public $limits;

    /** @var PleskX\Api\Struct\Webspace\Stat **/
    public $stat;

    /** @var PleskX\Api\Struct\Webspace\Preference **/
    public $prefs;

    /** @var PleskX\Api\Struct\Webspace\DiskUsage **/
    public $diskUsage;

    /** @var PleskX\Api\Struct\Webspace\Perfomance **/
    public $performance;

    /** @var PleskX\Api\Struct\Webspace\Subscriptions **/
    public $subscriptions;

    public function __construct($apiResponse)
    {
        $data = $apiResponse->data;
        $data->addChild('id',$apiResponse->id);
        $this->_initScalarProperties($data, [
            'id',
            'gen_info',
            'hosting',
            'limits',
            'stat',
            'prefs',
            'disk_usage',
            'performance',
            'subscriptions'
        ]);
    }
}