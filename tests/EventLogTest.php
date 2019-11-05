<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class EventLogTest extends TestCase
{

    public function testGet()
    {
        $events = $this->client->eventLog()->get();
        $this->assertGreaterThan(0, $events);

        $event = reset($events);
        $this->assertGreaterThan(0, $event->time);
    }

    public function testGetDetailedLog()
    {
        $events = $this->client->eventLog()->getDetailedLog();
        $this->assertGreaterThan(0, $events);

        $event = reset($events);
        $this->assertGreaterThan(0, $event->time);
        $this->assertGreaterThan(0, strlen($event->user));
    }

    public function testGetLastId()
    {
        $lastId = $this->client->eventLog()->getLastId();
        $this->assertGreaterThan(0, $lastId);
    }
}
