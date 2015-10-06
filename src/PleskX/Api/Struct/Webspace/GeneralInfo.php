<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class GeneralInfo extends \PleskX\Api\Struct
{
    /**  @var string */
    public $crDate;

    /**  @var string */
    public $name;

    /**  @var string */
    public $asciiName;

    /**  @var string */
    public $status;

    /**  @var integer */
    public $realSize;

    /**  @var string */
    public $ownerLogin;

    /**  @var string */
    public $dnsIpAddress;

    /**  @var string */
    public $htype;

    /**  @var string */
    public $guid;

    /**  @var string */
    public $vendorGuid;

    /**  @var string */
    public $externalId;

    /**  @var string */
    public $sbSiteUuid;

    /**  @var string */
    public $description;

    /**  @var string  */
    public $adminDescription;

    public function __construct($apiResponse)
    {
        $this->_initScalarProperties($apiResponse, [
            'cr_date',
            'name',
            ['ascii-name' => 'asciiName'],
            'status',
            'real_size',
            ['owner-login' => 'ownerLogin'],
            'dns_ip_address',
            'htype',
            'guid',
            ['vendor-guid' => 'vendorGuid'],
            ['external-id' => 'externalId'],
            ['sb-site-uuid' => 'sbSiteUuid'],
            'description',
            ['admin-description' => 'adminDescription']
        ]);
    }
}