<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class IpTest extends TestCase
{

    public function testGet()
    {
        $ips = $this->client->ip()->get();
        $this->assertGreaterThan(0, count($ips));

        $ip = reset($ips);
        $this->assertRegExp('/^[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}$/', $ip->ipAddress);
    }
}
