<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class DiskUsage extends \PleskX\Api\Struct
{

    /**  @var integer */
    public $httpdocs;
    /**  @var integer */
    public $httpsdocs;
    /**  @var integer */
    public $subdomains;
    /**  @var integer */
    public $webUsers;
    /**  @var integer */
    public $anonftp;
    /**  @var integer */
    public $logs;
    /**  @var integer */
    public $dbases;
    /**  @var integer */
    public $mailboxes;
    /**  @var integer */
    public $webapps;
    /**  @var integer */
    public $maillists;
    /**  @var integer */
    public $domaindumps;
    /**  @var integer */
    public $configs;
    /**  @var integer */
    public $chroot;
    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'httpdocs',
            'httpsdocs',
            'subdomains',
            'web_users',
            'anonftp',
            'logs',
            'dbases',
            'mailboxes',
            'webapps',
            'maillists',
            'domaindumps',
            'configs',
            'chroot'
        ]);
    }
}
