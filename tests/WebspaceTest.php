<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

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

    public function testGetPermissionDescriptor()
    {
        $descriptor = $this->_client->webspace()->getPermissionDescriptor();
        $this->assertInternalType('array', $descriptor->permissions);
        $this->assertGreaterThan(0, count($descriptor->permissions));
    }

    public function testGetLimitDescriptor()
    {
        $descriptor = $this->_client->webspace()->getLimitDescriptor();
        $this->assertInternalType('array', $descriptor->limits);
        $this->assertGreaterThan(0, count($descriptor->limits));
    }

    public function testGetPhysicalHostingDescriptor()
    {
        $descriptor = $this->_client->webspace()->getPhysicalHostingDescriptor();
        $this->assertInternalType('array', $descriptor->properties);
        $this->assertGreaterThan(0, count($descriptor->properties));

        $ftpLoginProperty = $descriptor->properties['ftp_login'];
        $this->assertEquals('ftp_login', $ftpLoginProperty->name);
        $this->assertEquals('string', $ftpLoginProperty->type);
    }

    public function testCreate()
    {
        $webspace = $this->_createWebspace();
        $this->assertInternalType('integer', $webspace->id);
        $this->assertGreaterThan(0, $webspace->id);

        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = $this->_createWebspace();
        $result = $this->_client->webspace()->delete('id', $webspace->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $webspace = $this->_createWebspace();
        $webspaceInfo = $this->_client->webspace()->get('id', $webspace->id);
        $this->assertEquals($this->webspaceSiteName, $webspaceInfo->name);

        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testData()
    {
        $webspace = $this->_createWebspace();
        $webspaceInfo = $this->_client->webspace()->getData('id', $webspace->id);
        $this->assertEquals($this->webspaceSiteName, $webspaceInfo->genInfo->name);
        $this->_client->webspace()->delete('id', $webspace->id);
    }


    public function testGetTrafficThisMonth()
    {
        $webspaceTraffic = $this->_client->webspace()->getTraffic('name', $this->trafficTestSite, 
            new DateTime('@'.strtotime('first day of this month'))
        );
        $this->assertInternalType('integer', $webspaceTraffic->httpIn);
    }

    public function testGetTrafficLastMonth()
    {
        $webspaceTraffic = $this->_client->webspace()->getTraffic('name', $this->trafficTestSite, 
            new DateTime('@'.strtotime('first day of previous month')),
            new DateTime('@'.strtotime('last day of previous month')))
        ;
        $this->assertInternalType('integer', $webspaceTraffic->httpIn);
    }

    public function testGetTraffic()
    {
        $webspaceTraffic = $this->_client->webspace()->getTraffic('name', $this->trafficTestSite );
        $this->assertInternalType('integer', $webspaceTraffic->httpIn);
    }

    public function testCreateSite()
    {
        $webspace = $this->_createWebspace();
        $site = $this->_createSite($webspace);

        $this->assertInternalType('integer', $site->id);
        $this->assertGreaterThan(0, $site->id);

        $this->_client->site()->delete('id', $site->id);
        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testDeleteSite()
    {
        $webspace = $this->_createWebspace();
        $site = $this->_createSite($webspace);

        $result = $this->_client->site()->delete('id', $site->id);
        $this->assertTrue($result);

        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testGetSite()
    {
        $webspace = $this->_createWebspace();
        $site = $this->_createSite($webspace);

        $siteInfo = $this->_client->site()->get('id', $site->id);
        $this->assertEquals($this->siteName, $siteInfo->name);

        $this->_client->site()->delete('id', $site->id);
        $this->_client->webspace()->delete('id', $webspace->id);
    }


}
