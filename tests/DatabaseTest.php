<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

class DatabaseTest extends TestCase
{

    private $webspaceSiteName = 'example-test-parent.dom';

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
                        'value' => 'passwordtest',
                    ]],
                    'ip_address' => $ipInfo->ipAddress
                ],
            ],
            'plan-name' => 'basic'
        ]);

    }


    private function _createDatabase( $webspace, $name = 'newdatabase' )
    {
        return $this->_client->database()->create([
            'webspace-id' => $webspace->id,
            'name' => $name,
            'type' => 'mysql'
        ]);
    }

    // public function testCreate()
    // {
    //     $webspace = $this->_createWebspace();
    //     $database = $this->_createDatabase( $webspace );
    //     $this->assertGreaterThan(0, $database->id);
    //     $this->_client->database()->delete('id', $database->id);
    //     $this->_client->webspace()->delete('id', $webspace->id);
    // }

    // public function testDelete()
    // {
    //     $webspace = $this->_createWebspace();
    //     $database = $this->_createDatabase( $webspace );
    //     $result = $this->_client->database()->delete('id', $database->id);
    //     $this->assertTrue($result);
    //     $this->_client->webspace()->delete('id', $webspace->id);
    // }

    // public function testGet()
    // {
    //     $webspace = $this->_createWebspace();
    //     $database = $this->_createDatabase( $webspace );
    //     $databaseInfo = $this->_client->database()->get('id', $database->id);
    //     $this->assertGreaterThan(0, $databaseInfo->id);
    //     $this->_client->database()->delete('id', $database->id);
    //     $this->_client->webspace()->delete('id', $webspace->id);
    // }

    public function testGetAllWebspace()
    {
        $webspace = $this->_createWebspace();
        $database1 = $this->_createDatabase( $webspace );
        $database2 = $this->_createDatabase( $webspace, 'database2' );

        $databaseInfo = $this->_client->database()->get('webspace-id', $webspace->id);
        $this->assertGreaterThan(0, $databaseInfo[0]->id);
        $this->assertGreaterThan(0, $databaseInfo[1]->id);

        $this->_client->database()->delete('id', $database1->id);
        $this->_client->database()->delete('id', $database2->id);
        $this->_client->webspace()->delete('id', $webspace->id);
    }


}