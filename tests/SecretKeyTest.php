<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class SecretKeyTest extends TestCase
{

    public function testCreate()
    {
        $keyId = $this->client->secretKey()->create('192.168.0.1');
        $this->assertRegExp('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/', $keyId);
        $this->client->secretKey()->delete($keyId);
    }

    public function testGetInfo()
    {
        $keyId = $this->client->secretKey()->create('192.168.0.1');
        $keyInfo = $this->client->secretKey()->getInfo($keyId);

        $this->assertEquals($keyId, $keyInfo->key);
        $this->assertEquals('192.168.0.1', $keyInfo->ipAddress);
        $this->assertEquals('admin', $keyInfo->login);

        $this->client->secretKey()->delete($keyId);
    }

    public function testDelete()
    {
        $keyId = $this->client->secretKey()->create('192.168.0.1');
        $this->client->secretKey()->delete($keyId);

        try {
            $this->client->secretKey()->getInfo($keyId);
            $this->fail("Secret key $keyId was not deleted.");
        } catch (Exception $exception) {
            $this->assertEquals(1013, $exception->getCode());
        }
    }
}
