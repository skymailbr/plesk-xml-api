<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskXTest;

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

        $ips = static::$_client->ip()->get();
        $ipInfo = reset($ips);

        return static::$_client->webspace()->create([
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
        return static::$_client->siteAlias()->create([
            'site-id' => $webspace->id,
            'name' => $this->aliasName
        ]);
    }

    public function testGetSearchBySiteName()
    {
        $webspace = $this->createWebspace();
        $siteAlias = $this->createSiteAlias($webspace);
        $siteAlias = static::$_client->siteAlias()->get('site-id', $webspace->id);
        $this->assertEquals($this->aliasName, $siteAlias[0]->name);
        static::$_client->siteAlias()->delete('id', $siteAlias[0]->id);
        static::$_client->webspace()->delete('id', $webspace->id);
    }


    public function testCreate()
    {
        $webspace = $this->_createWebspace();
        $siteAlias = $this->createSiteAlias($webspace);
        $this->assertIsInt($siteAlias->id);
        $this->assertGreaterThan(0, $siteAlias->id);
        static::$_client->siteAlias()->delete('id', $siteAlias->id);
        static::$_client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = $this->_createWebspace();
        $siteAlias = $this->createSiteAlias($webspace);
        $result = static::$_client->siteAlias()->delete('id', $siteAlias->id);
        $this->assertTrue($result);
        static::$_client->webspace()->delete('id', $webspace->id);
    }

    public function testRename()
    {

        $webspace = $this->_createWebspace();
        $siteAlias = $this->createSiteAlias($webspace);
        $result = static::$_client->siteAlias()->rename('id', $siteAlias->id, $this->aliasNewName);
        $this->assertTrue($result);
        $siteAlias = static::$_client->siteAlias()->get('id', $siteAlias->id);
        $this->assertEquals($this->aliasNewName, $siteAlias->name);
        static::$_client->siteAlias()->delete('id', $siteAlias->id);
        static::$_client->webspace()->delete('id', $webspace->id);
    }
}
