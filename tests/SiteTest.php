<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

class SiteTest extends TestCase
{

    private $webspaceSiteName = 'example-test-parent.dom';
    private $siteName = 'example-test-child.dom';

    /**
     * 
     *
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    private function _createWebspace()
    {

        $ips = $this->_client->ip()->get();
        $ipInfo = reset($ips);

        return $this->_client->webspace()->create([
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

    /**
     * @return \PleskX\Api\Struct\Webspace\Info
     */
    private function _createSite($webspace)
    {
        return $this->_client->site()->create([
            'gen_setup' => [
                'name' => $this->siteName,
                'webspace-id' => $webspace->id
            ],
        ]);
    }

    // public function testCreate()
    // {
    //     $webspace = $this->_createWebspace();
    //     $site = $this->_createSite($webspace);
    //     $this->assertInternalType('integer', $site->id);
    //     $this->assertGreaterThan(0, $site->id);
    //     $this->_client->site()->delete('id', $site->id);
    //     $this->_client->webspace()->delete('id', $webspace->id);
    // }

    // public function testDelete()
    // {
    //     $webspace = $this->_createWebspace();
    //     $site = $this->_createSite($webspace);
    //     $result = $this->_client->site()->delete('id', $site->id);
    //     $this->assertTrue($result);
    //     $this->_client->webspace()->delete('id', $webspace->id);
    // }

    // public function testGet()
    // {
    //     $webspace = $this->_createWebspace();
    //     $site = $this->_createSite($webspace);
    //     $siteInfo = $this->_client->site()->get('id', $site->id);
    //     $this->assertEquals($this->siteName, $siteInfo->name);
    //     $this->_client->site()->delete('id', $site->id);
    //     $this->_client->webspace()->delete('id', $webspace->id);
    // }

    public function testData()
    {
        $webspace = $this->_createWebspace();
        $site = $this->_createSite($webspace);
        $siteInfo = $this->_client->site()->getData('id', $site->id);
        $this->assertEquals($this->siteName, $siteInfo->genInfo->name);
        $this->assertEquals($site->id, $siteInfo->id);
        $this->_client->site()->delete('id', $site->id);
        $this->_client->webspace()->delete('id', $webspace->id);
    }

    // public function testDataSearchBySpace()
    // {
    //     $webspace = $this->_createWebspace();
    //     $site = $this->_createSite($webspace);
    //     $siteInfo = $this->_client->site()->getData('parent-id', $webspace->id);
    //     $this->assertEquals($this->siteName, $siteInfo[0]->genInfo->name);
    //     $this->_client->site()->delete('id', $site->id);
    //     $this->_client->webspace()->delete('id', $webspace->id);
    // }

}
