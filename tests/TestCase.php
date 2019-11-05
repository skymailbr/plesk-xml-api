<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

use PleskX\Api\Client as PleskClient;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{

    /** @var PleskClient */
    protected $client;

    protected function setUp(): void
    {
        $host = getenv('REMOTE_HOST');
        $login = getenv('REMOTE_LOGIN');
        $password = getenv('REMOTE_PASSWORD');

        $this->client = new PleskClient($host);
        $this->client->setCredentials($login, $password);
    }
}
