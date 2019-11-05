<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class ServicePlanTest extends TestCase
{

    private $webspaceSiteName = 'example-test-parent.dom';

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

    public function testGet()
    {
        $webspace = $this->createWebspace();
        $webspaceInfo = $this->client->webspace()->getData('id', $webspace->id);
        $servicePlan = $this->client->servicePlan()->get('guid', $webspaceInfo->subscriptions->data[0]->plan->planGuid);
        $this->assertGreaterThan(0, $servicePlan->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testGetAll()
    {
        $servicePlan = $this->client->servicePlan()->getAll();
        $this->assertGreaterThan(0, $servicePlan[0]->id);
    }
}
