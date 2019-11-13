<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskXTest;

class FtpUserTest extends TestCase
{

    /** @var \PleskX\Api\Struct\Webspace\Info */
    private static $webspace;

    /** @var string */
    private static $webspaceName;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        static::$webspace = static::_createWebspace();
        $webSpaceInfo = static::$_client->webspace()->get('id', static::$webspace->id);
        static::$webspaceName = $webSpaceInfo->name;
    }

    private function createFtpUser($name = '')
    {
        if (empty($name)) {
            $name = 'ftpu' . uniqid();
        }

        return static::$_client->ftpUser()->create([
            'name' => $name,
            'password' => Utility\PasswordProvider::STRONG_PASSWORD,
            'home' => '',
            'webspace-id' => static::$webspace->id
        ]);
    }

    public function testCreate()
    {
        $ftpUser = $this->createFtpUser();
        $this->assertGreaterThan(0, $ftpUser->id);
        static::$_client->ftpUser()->delete('id', $ftpUser->id);
    }

    public function testSet()
    {
        $ftpUser = $this->createFtpUser();
        $result = static::$_client->ftpuser()->set('id', $ftpUser->id, ['password' => 'kjklasdjlkaj']);
        $this->assertGreaterThan(0, $result->id);
        static::$_client->ftpuser()->delete('id', $ftpUser->id);
    }

    public function testDelete()
    {
        $ftpUser = $this->createFtpUser();
        $result = static::$_client->ftpuser()->delete('id', $ftpUser->id);
        $this->assertTrue($result);
    }

    public function testGet()
    {
        $ftpUser = $this->createFtpUser();
        $ftpUserInfo = static::$_client->ftpUser()->get('id', $ftpUser->id);
        $this->assertGreaterThan(0, $ftpUserInfo->id);
        static::$_client->ftpuser()->delete('id', $ftpUser->id);
    }

    public function testGetAllWebspace()
    {
        $ftpUser1 = $this->createFtpUser();
        $ftpUser2 = $this->createFtpUser('ftpuser22');

        $ftpUserInfo = static::$_client->ftpUser()->getAll('webspace-id', static::$webspace->id);
        $this->assertGreaterThan(0, $ftpUserInfo[0]->id);
        $this->assertGreaterThan(0, $ftpUserInfo[1]->id);

        static::$_client->ftpuser()->delete('id', $ftpUser1->id);
        static::$_client->ftpuser()->delete('id', $ftpUser2->id);
    }
}
