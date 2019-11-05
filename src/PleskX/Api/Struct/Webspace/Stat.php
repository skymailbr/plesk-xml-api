<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class Stat extends \PleskX\Api\Struct
{
    /**  @var integer */
    public $traffic;
    /**  @var integer */
    public $subdom;
    /**  @var integer */
    public $wu;
    /**  @var integer */
    public $box;
    /**  @var integer */
    public $redir;
    /**  @var integer */
    public $mg;
    /**  @var integer */
    public $resp;
    /**  @var integer */
    public $maillists;
    /**  @var integer */
    public $db;
    /**  @var integer */
    public $mssqlDb;
    /**  @var integer */
    public $webapps;
    /**  @var integer */
    public $trafficPrevday;
    /**  @var integer */
    public $domains;
    /**  @var integer */
    public $sites;
    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'traffic',
            'subdom',
            'wu',
            'box',
            'redir',
            'mg',
            'resp',
            'maillists',
            'db',
            'mssql_db',
            'webapps',
            'traffic_prevday',
            'domains',
            'sites'
        ]);
    }
}
