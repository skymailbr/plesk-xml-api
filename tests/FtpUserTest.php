<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

class FtpUserTest extends TestCase
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


    private function _createFtpUser( $webspace )
    {
        return $this->_client->ftpUser()->create([
            'name' => 'newftpuser',
            'password' => 'userpassword',
            'home' => '',
            'webspace-id' => $webspace->id
        ]);
    }

    public function testCreate()
    {
        $webspace = $this->_createWebspace();
        $ftpuser = $this->_createFtpUser( $webspace );
        $this->assertGreaterThan(0, $ftpuser->id);
        $this->_client->ftpuser()->delete('id', $ftpuser->id);
        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testDelete()
    {
        $webspace = $this->_createWebspace();
        $ftpuser = $this->_createFtpUser( $webspace );
        $result = $this->_client->ftpuser()->delete('id', $ftpuser->id);
        $this->assertTrue($result);
        $this->_client->webspace()->delete('id', $webspace->id);
    }

    public function testGet()
    {
        $webspace = $this->_createWebspace();
        $ftpuser = $this->_createFtpUser( $webspace );
        $ftpUserInfo = $this->_client->ftpUser()->get('id', $ftpuser->id);
        $this->assertGreaterThan(0, $ftpUserInfo->id);
        $this->_client->ftpuser()->delete('id', $ftpuser->id);
        $this->_client->webspace()->delete('id', $webspace->id);
    }

}
