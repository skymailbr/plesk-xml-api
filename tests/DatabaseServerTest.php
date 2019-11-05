<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class DatabaseServerTest extends TestCase
{

    public function testGetSupportedTypes()
    {
        $types = $this->client->databaseServer()->getSupportedTypes();
        $this->assertGreaterThan(0, count($types));
        $this->assertContains('mysql', $types);
    }


    public function testGet()
    {
        $servers = $this->client->databaseServer()->get();
        $this->assertGreaterThan(0, $servers[0]->id);
    }
}
