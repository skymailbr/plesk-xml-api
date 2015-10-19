<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

class PHPHandlerTest extends TestCase
{

    public function testGet()
    {
        $get = $this->_client->phpHandler()->get('id', 'cgi');
        $this->assertEquals('cgi', $get->id);
    }

}