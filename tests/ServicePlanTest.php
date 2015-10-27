<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

class ServicePlanTest extends TestCase
{

    private $webspaceSiteName = 'example-test-parent.dom';

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
    public function testGet()
    {
        $webspace = $this->_createWebspace();
        $webspaceInfo = $this->_client->webspace()->getData('id', $webspace->id);
        $servicePlan = $this->_client->servicePlan()->get('guid', $webspaceInfo->subscriptions->data[0]->plan->planGuid);
        $this->assertGreaterThan(0, $servicePlan->id);
        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testGetAll()
    {
        $servicePlan = $this->_client->servicePlan()->getAll();
        $this->assertGreaterThan(0, $servicePlan[0]->id);
    }

}