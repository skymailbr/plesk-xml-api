<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\SiteAlias;

class Preferences extends \PleskX\Api\Struct
{
    /** @var boolean **/
    public $web;
    /** @var boolean * */
    public $tomcat;
    /** @var boolean * */
    public $seoRedirect;
    public function __construct($apiResponse)
    {
        $this->initScalarProperties($apiResponse, [
            'web',
            'tomcat',
            'seo-redirect',
        ]);
    }
}
