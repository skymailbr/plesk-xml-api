<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class Subscription extends \PleskX\Api\Struct
{
    /** @var boolean */
    public $locked;
    /** @var boolean */
    public $synchronized;
    /** @var Plan */
    public $plan;
    public function __construct($apiResponse)
    {
        $this->locked = $apiResponse->locked;
        $this->synchronized = $apiResponse->synchronized;
        $this->plan = new Plan();
        $this->plan->planGuid = $apiResponse->plan->{'plan-guid'};
    }
}
