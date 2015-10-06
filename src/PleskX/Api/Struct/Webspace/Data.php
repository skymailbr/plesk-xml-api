<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class Data extends \PleskX\Api\Struct
{
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

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'gen_info',
            'hosting',
            'limits',
            'stat',
            'prefs',
            'disk_usage',
            'performance'
        ]);
    }
}