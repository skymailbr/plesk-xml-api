<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class UserTest extends TestCase
{

    private $_customer;

    private $_userProperties = [
        'login' => 'mike-test',
        'passwd' => 'simple-password',
        'owner-guid' => null,
        'name' => 'Mike Black',
        'email' => 'mike@example.com',
    ];

    protected function setUp(): void
    {
        parent::setUp();

        $this->_customer = $this->client->customer()->create([
            'pname' => 'John Smith',
            'login' => 'john-unit-test',
            'passwd' => 'simple-password',
        ]);
        $this->_userProperties['owner-guid'] = $this->_customer->guid;
    }

    protected function tearDown(): void
    {
        $this->client->customer()->delete('id', $this->_customer->id);
    }

    public function testCreate()
    {
        $user = $this->client->user()->create('Application User', $this->_userProperties);
        $this->assertIsInt($user->id);
        $this->assertGreaterThan(0, $user->id);

        $this->client->user()->delete('guid', $user->guid);
    }

    public function testDelete()
    {
        $user = $this->client->user()->create('Application User', $this->_userProperties);
        $result = $this->client->user()->delete('guid', $user->guid);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $user = $this->client->user()->create('Application User', $this->_userProperties);
        $userInfo = $this->client->user()->get('guid', $user->guid);
        $this->assertEquals('mike-test', $userInfo->login);
        $this->assertEquals('Mike Black', $userInfo->name);
        $this->assertEquals('mike@example.com', $userInfo->email);
        $this->assertEquals($user->guid, $userInfo->guid);

        $this->client->user()->delete('guid', $user->guid);
    }
}
