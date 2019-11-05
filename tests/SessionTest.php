<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class SessionTest extends TestCase
{

    public function testGet()
    {
        $sessionId = $this->client->server()->createSession('admin', '127.0.0.1');
        $sessions = $this->client->session()->get();
        $this->assertArrayHasKey($sessionId, $sessions);

        $sessionInfo = $sessions[$sessionId];
        $this->assertEquals('admin', $sessionInfo->login);
        $this->assertEquals('127.0.0.1', $sessionInfo->ipAddress);
        $this->assertEquals($sessionId, $sessionInfo->id);
    }

    public function testTerminate()
    {
        $sessionId = $this->client->server()->createSession('admin', '127.0.0.1');
        $this->client->session()->terminate($sessionId);
        $sessions = $this->client->session()->get();
        $this->assertArrayNotHasKey($sessionId, $sessions);
    }
}
