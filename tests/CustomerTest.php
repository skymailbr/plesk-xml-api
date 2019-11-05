<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class CustomerTest extends TestCase
{

    private $_customerProperties = [
        'pname' => 'John Smith',
        'login' => 'john-unit-test',
        'passwd' => 'simple-password',
    ];

    public function testCreate()
    {
        $customer = $this->client->customer()->create($this->_customerProperties);
        $this->assertIsInt($customer->id);
        $this->assertGreaterThan(0, $customer->id);

        $this->client->customer()->delete('id', $customer->id);
    }

    public function testDelete()
    {
        $customer = $this->client->customer()->create($this->_customerProperties);
        $result = $this->client->customer()->delete('id', $customer->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $customer = $this->client->customer()->create($this->_customerProperties);
        $customerInfo = $this->client->customer()->get('id', $customer->id);
        $this->assertEquals('John Smith', $customerInfo->personalName);
        $this->assertEquals('john-unit-test', $customerInfo->login);
        $this->assertEquals($customer->id, $customerInfo->id);

        $this->client->customer()->delete('id', $customer->id);
    }

    public function testGetDomainList()
    {
        $domainList = $this->client->customer()->getDomainList('login', 1);
        $this->assertGreaterThan(0, $domainList[0]->id);
    }

    public function testGetAll()
    {
        $customerList = $this->client->customer()->get();
        $this->assertGreaterThan(0, $customerList[0]->id);
    }
}
