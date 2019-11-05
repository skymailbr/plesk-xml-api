<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace Tests;

class DatabaseTest extends TestCase
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


    private function createDatabase($webspace, $name = 'newdatabase')
    {
        return $this->client->database()->create([
            'webspace-id' => $webspace->id,
            'name' => $name,
            'type' => 'mysql'
        ]);
    }

    private function createUser($database, $login = 'newuserdatabase')
    {
        return $this->client->database()->createUser([
            'db-id' => $database->id,
            'login' => $login,
            'password' => 'dbpassword'
        ]);
    }

    public function testCreate()
    {
        $webspace = $this->createWebspace();
        $database = $this->createDatabase($webspace);
        $this->assertGreaterThan(0, $database->id);
        $this->client->database()->delete('id', $database->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = $this->createWebspace();
        $database = $this->createDatabase($webspace);
        $result = $this->client->database()->delete('id', $database->id);
        $this->assertTrue($result);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testGet()
    {
        $webspace = $this->createWebspace();
        $database = $this->createDatabase($webspace);
        $databaseInfo = $this->client->database()->get('id', $database->id);
        $this->assertGreaterThan(0, $databaseInfo->id);
        $this->client->database()->delete('id', $database->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testGetAllWebspace()
    {
        $webspace = $this->createWebspace();
        $database1 = $this->createDatabase($webspace);
        $database2 = $this->createDatabase($webspace, 'database2');

        $databaseInfo = $this->client->database()->get('webspace-id', $webspace->id);
        $this->assertGreaterThan(0, $databaseInfo[0]->id);
        $this->assertGreaterThan(0, $databaseInfo[1]->id);

        $this->client->database()->delete('id', $database1->id);
        $this->client->database()->delete('id', $database2->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testCreateUser()
    {
        $webspace = $this->createWebspace();
        $database = $this->createDatabase($webspace);
        $user = $this->createUser($database);
        $this->assertGreaterThan(0, $user->id);
        $this->client->database()->deleteUser('id', $user->id);
        $this->client->database()->delete('id', $database->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testSetUser()
    {
        $webspace = $this->createWebspace();
        $database = $this->createDatabase($webspace);
        $user = $this->createUser($database);
        $result = $this->client->database()->setUser($user->id, ['password' => 'daskljaskljdaskladj']);
        $this->assertTrue($result);
        $this->client->database()->deleteUser('id', $user->id);
        $this->client->database()->delete('id', $database->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testDeleteUser()
    {
        $webspace = $this->createWebspace();
        $database = $this->createDatabase($webspace);
        $user = $this->createUser($database);
        $result = $this->client->database()->deleteUser('id', $user->id);
        $this->assertTrue($result);
        $this->client->database()->delete('id', $database->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }

    public function testGetUser()
    {
        $webspace = $this->createWebspace();
        $database = $this->createDatabase($webspace);
        $user = $this->createUser($database);
        $userInfo = $this->client->database()->getUser('id', $user->id);
        $this->assertGreaterThan(0, $userInfo->id);
        $this->client->database()->deleteUser('id', $user->id);
        $this->client->database()->delete('id', $database->id);
        $this->client->webspace()->delete('id', $webspace->id);
    }
}
