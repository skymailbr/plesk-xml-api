<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class ResellerTest extends TestCase
{

    private $_resellerProperties = [
        'pname' => 'John Reseller',
        'login' => 'reseller-unit-test',
        'passwd' => 'simple-password',
    ];

    public function testCreate()
    {
        $reseller = $this->client->reseller()->create($this->_resellerProperties);
        $this->assertIsInt($reseller->id);
        $this->assertGreaterThan(0, $reseller->id);

        $this->client->reseller()->delete('id', $reseller->id);
    }

    public function testDelete()
    {
        $reseller = $this->client->reseller()->create($this->_resellerProperties);
        $result = $this->client->reseller()->delete('id', $reseller->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $reseller = $this->client->reseller()->create($this->_resellerProperties);
        $resellerInfo = $this->client->reseller()->get('id', $reseller->id);
        $this->assertEquals('John Reseller', $resellerInfo->personalName);
        $this->assertEquals('reseller-unit-test', $resellerInfo->login);

        $this->client->reseller()->delete('id', $reseller->id);
    }
}
