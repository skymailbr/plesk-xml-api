<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class ServerTest extends TestCase
{

    public function testGetProtos()
    {
        $protos = $this->client->server()->getProtos();
        $this->assertIsArray($protos);
        $this->assertContains('1.6.3.0', $protos);
    }

    public function testGetGenInfo()
    {
        $generalInfo = $this->client->server()->getGeneralInfo();
        $this->assertGreaterThan(0, strlen($generalInfo->serverName));
        $this->assertRegExp(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
            $generalInfo->serverGuid
        );
        $this->assertEquals('standard', $generalInfo->mode);
    }

    public function testGetPreferences()
    {
        $preferences = $this->client->server()->getPreferences();
        $this->assertIsInt($preferences->statTtl);
        $this->assertGreaterThan(0, $preferences->statTtl);
        $this->assertEquals(0, $preferences->restartApacheInterval);
    }

    public function testGetAdmin()
    {
        $admin = $this->client->server()->getAdmin();
        $this->assertGreaterThan(0, strlen($admin->companyName));
        $this->assertGreaterThan(0, strlen($admin->name));
        $this->assertContains('@', $admin->email);
    }

    public function testGetKeyInfo()
    {
        $keyInfo = $this->client->server()->getKeyInfo();
        $this->assertIsArray($keyInfo);
        $this->assertGreaterThan(0, count($keyInfo));
        $this->assertArrayHasKey('plesk_key_id', $keyInfo);
        $this->assertArrayHasKey('product_version', $keyInfo);
    }

    public function testGetComponents()
    {
        $components = $this->client->server()->getComponents();
        $this->assertIsArray($components);
        $this->assertGreaterThan(0, count($components));
        $this->assertArrayHasKey('psa', $components);
        $this->assertArrayHasKey('php', $components);
    }

    public function testGetServiceStates()
    {
        $serviceStates = $this->client->server()->getServiceStates();
        $this->assertIsArray($serviceStates);
        $this->assertGreaterThan(0, count($serviceStates));
        $this->assertArrayHasKey('web', $serviceStates);

        $webService = $serviceStates['web'];
        $this->assertIsArray($webService);
        $this->assertArrayHasKey('id', $webService);
        $this->assertArrayHasKey('title', $webService);
        $this->assertArrayHasKey('state', $webService);
        $this->assertEquals('running', $webService['state']);
    }

    public function testGetSessionPreferences()
    {
        $preferences = $this->client->server()->getSessionPreferences();
        $this->assertIsInt($preferences->loginTimeout);
        $this->assertGreaterThan(0, $preferences->loginTimeout);
    }

    public function testGetShells()
    {
        $shells = $this->client->server()->getShells();
        $this->assertIsArray($shells);
        $this->assertGreaterThan(0, count($shells));
        $this->assertArrayHasKey('/bin/bash', $shells);

        $bash = $shells['/bin/bash'];
        $this->assertEquals('/bin/bash', $bash);
    }

    public function testGetNetworkInterfaces()
    {
        $netInterfaces = $this->client->server()->getNetworkInterfaces();
        $this->assertIsArray($netInterfaces);
        $this->assertGreaterThan(0, count($netInterfaces));
    }

    public function testGetStatistics()
    {
        $stats = $this->client->server()->getStatistics();
        $this->assertIsInt($stats->objects->clients);
        $this->assertEquals('psa', $stats->version->internalName);
    }

    public function testGetSiteIsolationConfig()
    {
        $config = $this->client->server()->getSiteIsolationConfig();
        $this->assertIsArray($config);
        $this->assertGreaterThan(0, count($config));
        $this->assertArrayHasKey('php', $config);
    }

    public function testGetUpdatesInfo()
    {
        $updatesInfo = $this->client->server()->getUpdatesInfo();
        $this->assertInternalType('boolean', $updatesInfo->installUpdatesAutomatically);
    }
}
