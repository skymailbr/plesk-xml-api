<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class SiteTest extends TestCase
{

    private $webspaceSiteName = 'example-test-parent.dom';
    private $siteName = 'example-test-child.dom';

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
                    [
                        'property' => [
                            'name' => 'ftp_login',
                            'value' => 'ftpusertest',
                        ]
                    ],
                    [
                        'property' => [
                            'name' => 'ftp_password',
                            'value' => 'ftpuserpasswordtest',
                        ]
                    ],
                    'ip_address' => $ipInfo->ipAddress
                ],
            ],
            'plan-name' => 'basic'
        ]);
    }

    /**
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    private function createSite($webspace)
    {
        return $this->client->site()->create([
            'gen_setup' => [
                'name' => $this->siteName,
                'webspace-id' => $webspace->id
            ],
        ]);
    }

    public function testCreate()
    {
        $webspace = $this->createWebspace();
        $site = $this->createSite($webspace);
        $this->assertIsInt($site->id);
        $this->assertGreaterThan(0, $site->id);
        $this->client->site()->delete('id', $site->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = $this->createWebspace();
        $site = $this->createSite($webspace);
        $result = $this->client->site()->delete('id', $site->id);
        $this->assertTrue($result);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testGet()
    {
        $webspace = $this->createWebspace();
        $site = $this->createSite($webspace);
        $siteInfo = $this->client->site()->get('id', $site->id);
        $this->assertEquals($this->siteName, $siteInfo->name);
        $this->client->site()->delete('id', $site->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testData()
    {
        $webspace = $this->createWebspace();
        $site = $this->createSite($webspace);
        $siteInfo = $this->client->site()->getData('id', $site->id);
        $this->assertEquals($this->siteName, $siteInfo->genInfo->name);
        $this->assertEquals($site->id, $siteInfo->id);
        $this->client->site()->delete('id', $site->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testDataSearchBySpace()
    {
        $webspace = $this->createWebspace();
        $site = $this->createSite($webspace);
        $siteInfo = $this->client->site()->getData('parent-id', $webspace->id);
        $this->assertEquals($this->siteName, $siteInfo[0]->genInfo->name);
        $this->client->site()->delete('id', $site->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }
}
