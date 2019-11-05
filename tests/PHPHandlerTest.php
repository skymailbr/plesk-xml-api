<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class PHPHandlerTest extends TestCase
{

    public function testGet()
    {
        $get = $this->client->phpHandler()->get('id', 'cgi');
        $this->assertEquals('cgi', $get->id);
    }
}
