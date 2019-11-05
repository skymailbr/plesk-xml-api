<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class FtpUserTest extends TestCase
{

    private $webspaceSiteName = 'example-test-parent.dom';

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
                        'value' => 'passwordtest',
                    ]],
                    'ip_address' => $ipInfo->ipAddress
                ],
            ],
            'plan-name' => 'basic'
        ]);
    }


    private function createFtpUser($webspace, $name = 'newftpuser')
    {
        return $this->client->ftpUser()->create([
            'name' => $name,
            'password' => 'userpassword',
            'home' => '',
            'webspace-id' => $webspace->id
        ]);
    }

    public function testCreate()
    {
        $webspace = $this->createWebspace();
        $ftpuser = $this->createFtpUser($webspace);
        $this->assertGreaterThan(0, $ftpuser->id);
        $this->client->ftpuser()->delete('id', $ftpuser->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testSet()
    {
        $webspace = $this->createWebspace();
        $ftpuser = $this->createFtpUser($webspace);
        $result = $this->client->ftpuser()->set('id', $ftpuser->id, ['password' => 'kjklasdjlkaj']);
        $this->assertGreaterThan(0, $result->id);
        $this->client->ftpuser()->delete('id', $ftpuser->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = $this->createWebspace();
        $ftpuser = $this->createFtpUser($webspace);
        $result = $this->client->ftpuser()->delete('id', $ftpuser->id);
        $this->assertTrue($result);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testGet()
    {
        $webspace = $this->createWebspace();
        $ftpuser = $this->createFtpUser($webspace);
        $ftpUserInfo = $this->client->ftpUser()->get('id', $ftpuser->id);
        $this->assertGreaterThan(0, $ftpUserInfo->id);
        $this->client->ftpuser()->delete('id', $ftpuser->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testGetAllWebspace()
    {
        $webspace = $this->createWebspace();
        $ftpuser1 = $this->createFtpUser($webspace);
        $ftpuser2 = $this->createFtpUser($webspace, 'ftpuser22');

        $ftpUserInfo = $this->client->ftpUser()->get('webspace-id', $webspace->id);
        $this->assertGreaterThan(0, $ftpUserInfo[0]->id);
        $this->assertGreaterThan(0, $ftpUserInfo[1]->id);

        $this->client->ftpuser()->delete('id', $ftpuser1->id);
        $this->client->ftpuser()->delete('id', $ftpuser2->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }
}
