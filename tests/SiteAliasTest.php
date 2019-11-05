<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class SiteAliasTest extends TestCase
{
    private $webspaceSiteName = 'example-test-parent.dom';
    private $aliasName = 'example-test-alias.dom';
    private $aliasNewName = 'example-new-name-alias.dom';

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
     * @return \PleskX\Api\Struct\SiteAlias\Info
     */
    private function createSiteAlias($webspace)
    {
        return $this->client->siteAlias()->create([
            'site-id' => $webspace->id,
            'name' => $this->aliasName
        ]);
    }

    public function testGetSearchBySiteName()
    {
        $webspace = $this->createWebspace();
        $siteAlias = $this->createSiteAlias($webspace);
        $siteAlias = $this->client->siteAlias()->get('site-id', $webspace->id);
        $this->assertEquals($this->aliasName, $siteAlias[0]->name);
        $this->client->siteAlias()->delete('id', $siteAlias[0]->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }


    // public function testCreate() {
    //     $webspace = $this->_createWebspace();
    //     $siteAlias = $this->_createSiteAlias($webspace);
    //     $this->assertIsInt($siteAlias->id);
    //     $this->assertGreaterThan(0, $siteAlias->id);
    //     $this->_client->siteAlias()->delete('id', $siteAlias->id);
    //     $this->_client->webspace()->delete('id', $webspace->id);
    // }

    // public function testDelete() {
    //     $webspace = $this->_createWebspace();
    //     $siteAlias = $this->_createSiteAlias($webspace);
    //     $result = $this->_client->siteAlias()->delete('id', $siteAlias->id);
    //     $this->assertTrue($result);
    //     $this->_client->webspace()->delete('id', $webspace->id);
    // }

    // public function testRename() {

    //     $webspace = $this->_createWebspace();
    //     $siteAlias = $this->_createSiteAlias($webspace);
    //     $result = $this->_client->siteAlias()->rename('id', $siteAlias->id, $this->aliasNewName);
    //     $this->assertTrue($result);
    //     $siteAlias = $this->_client->siteAlias()->get('id', $siteAlias->id);
    //     $this->assertEquals($this->aliasNewName, $siteAlias->name);
    //     $this->_client->siteAlias()->delete('id', $siteAlias->id);
    //     $this->_client->webspace()->delete('id', $webspace->id);
    // }
}
