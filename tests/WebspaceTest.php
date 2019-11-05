<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class WebspaceTest extends TestCase
{

    private $webspaceSiteName = 'example-test-parent.dom';
    private $siteName = 'example-test-child.dom';
    private $trafficTestSite = 'worksecurity.net'; //SITE HOSTED BY MY PLESK

    /**
     *
     *
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    private function createWebspace()
    {

        $ips = $this->client->ip()->get();
        $ipInfo = reset($ips);

        return $this->client->webspace()->create([
            'gen_setup' => [
                'name' => $this->webspaceSiteName,
                'ip_address' => $ipInfo->ipAddress,
                'htype' => 'vrt_hst'
            ],
            'hosting' => [
                'vrt_hst' => [
                    ['property' => [
                        'name' => 'ftp_login',
                        'value' => 'ftpusertest',
                    ]],
                    ['property' => [
                        'name' => 'ftp_password',
                        'value' => 'ftpuserpasswordtest',
                    ]],
                    'ip_address' => $ipInfo->ipAddress
                ],
            ],
            'plan-name' => 'basic'
        ]);
    }


    public function testGetPermissionDescriptor()
    {
        $descriptor = $this->client->webspace()->getPermissionDescriptor();
        $this->assertIsArray($descriptor->permissions);
        $this->assertGreaterThan(0, count($descriptor->permissions));
    }

    public function testGetLimitDescriptor()
    {
        $descriptor = $this->client->webspace()->getLimitDescriptor();
        $this->assertIsArray($descriptor->limits);
        $this->assertGreaterThan(0, count($descriptor->limits));
    }

    public function testGetPhysicalHostingDescriptor()
    {
        $descriptor = $this->client->webspace()->getPhysicalHostingDescriptor();
        $this->assertIsArray($descriptor->properties);
        $this->assertGreaterThan(0, count($descriptor->properties));

        $ftpLoginProperty = $descriptor->properties['ftp_login'];
        $this->assertEquals('ftp_login', $ftpLoginProperty->name);
        $this->assertEquals('string', $ftpLoginProperty->type);
    }

    public function testCreate()
    {
        $webspace = $this->createWebspace();
        $this->assertIsInt($webspace->id);
        $this->assertGreaterThan(0, $webspace->id);

        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = $this->createWebspace();
        $result = $this->client->webspace()->delete('id', $webspace->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $webspace = $this->createWebspace();
        $webspaceInfo = $this->client->webspace()->get('id', $webspace->id);
        $this->assertEquals($this->webspaceSiteName, $webspaceInfo->name);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testSet()
    {
        $webspace = $this->createWebspace();
        $result = $this->client->webspace()->set('id', $webspace->id,
            ['hosting' => ['vrt_hst' => ['property' => ['name' => 'ftp_password', 'value' => 'kjklasdjlkaj']]]]);
        $this->assertGreaterThan(0, $result->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testData()
    {
        $webspace = $this->createWebspace();
        $webspaceInfo = $this->client->webspace()->getData('id', $webspace->id);
        $this->assertEquals($this->webspaceSiteName, $webspaceInfo->genInfo->name);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testDataAll()
    {
        $webspaceInfo = $this->client->webspace()->getData();
        $this->assertGreaterThan(0, $webspaceInfo[0]->id);
    }

    public function testGetTrafficThisMonth()
    {
        $webspaceTraffic = $this->client->webspace()->getTraffic(
            'name',
            $this->trafficTestSite,
            new \DateTime('@' . strtotime('first day of this month'))
        );
        $this->assertIsInt($webspaceTraffic->httpIn);
    }

    public function testGetTrafficLastMonth()
    {
        $webspaceTraffic = $this->client->webspace()->getTraffic(
            'name',
            $this->trafficTestSite,
            new \DateTime('@' . strtotime('first day of previous month')),
            new \DateTime('@' . strtotime('last day of previous month'))
        );
        $this->assertIsInt($webspaceTraffic->httpIn);
    }

    public function testGetTraffic()
    {
        $webspaceTraffic = $this->client->webspace()->getTraffic('name', $this->trafficTestSite);
        $this->assertIsInt($webspaceTraffic->httpIn);
    }
}
