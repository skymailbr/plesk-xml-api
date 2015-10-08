<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class Subscriptions extends \PleskX\Api\Struct
{
    /** @var array Subscriptions */
    public $data;

    public function __construct($apiResponse)
    {
        $this->data = [];
        foreach ($apiResponse->subscription as $sb) {
            $this->data[] = new Subscription($sb);
        }
    }
}