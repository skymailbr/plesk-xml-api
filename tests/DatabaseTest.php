<?php
// Copyright 1999-2016. Parallels IP Holdings GmbH.

class DatabaseTest extends TestCase
{
    /**
     * @var \PleskX\Api\Struct\Webspace\Info
     */
    private static $_webspace;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();
        static::$_webspace = static::_createWebspace('example.dom');
    }

    public static function tearDownAfterClass()
    {
        parent::tearDownAfterClass();
        static::$_client->webspace()->delete('id', static::$_webspace->id);
    }

    public function testCreate()
    {
        $database = $this->_createDatabase([
            'webspace-id' => static::$_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        static::$_client->database()->delete('id', $database->id);
    }

    public function testCreateUser()
    {
        $database = $this->_createDatabase([
            'webspace-id' => static::$_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $user = $this->_createUser([
            'db-id' => $database->id,
            'login' => 'test_user1',
            'password' => 'setup1Q',
        ]);
        static::$_client->database()->deleteUser('id', $user->id);
        static::$_client->database()->delete('id', $database->id);

    }

    public function testGetById()
    {
        $database = $this->_createDatabase([
            'webspace-id' => static::$_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);

        $db = static::$_client->database()->get('id', $database->id);
        $this->assertEquals('test1', $db->name);
        $this->assertEquals('mysql', $db->type);
        $this->assertEquals(static::$_webspace->id, $db->webspaceId);
        $this->assertEquals(1, $db->dbServerId);

        static::$_client->database()->delete('id', $database->id);
    }

    public function testGetAllByWebspaceId()
    {
        $db1 = $this->_createDatabase([
            'webspace-id' => static::$_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $db2 = $this->_createDatabase([
            'webspace-id' => static::$_webspace->id,
            'name' => 'test2',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $databases = static::$_client->database()->getAll('webspace-id', static::$_webspace->id);
        $this->assertEquals('test1', $databases[0]->name);
        $this->assertEquals('test2', $databases[1]->name);
        $this->assertEquals(static::$_webspace->id, $databases[0]->webspaceId);
        $this->assertEquals(1, $databases[1]->dbServerId);

        static::$_client->database()->delete('id', $db1->id);
        static::$_client->database()->delete('id', $db2->id);
    }

    public function testGetUserById()
    {
        $database = $this->_createDatabase([
            'webspace-id' => static::$_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);

        $user = $this->_createUser([
            'db-id' => $database->id,
            'login' => 'test_user1',
            'password' => 'setup1Q',
        ]);

        $dbUser = static::$_client->database()->getUser('id', $user->id);
        $this->assertEquals('test_user1', $dbUser->login);
        $this->assertEquals($database->id, $dbUser->dbId);

        static::$_client->database()->deleteUser('id', $user->id);
        static::$_client->database()->delete('id', $database->id);
    }

    public function testGetAllUsersByDbId()
    {
        $db1 = $this->_createDatabase([
            'webspace-id' => static::$_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $db2 = $this->_createDatabase([
            'webspace-id' => static::$_webspace->id,
            'name' => 'test2',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $user1 = $this->_createUser([
            'db-id' => $db1->id,
            'login' => 'test_user1',
            'password' => 'setup1Q',
        ]);

        $user2 = $this->_createUser([
            'db-id' => $db1->id,
            'login' => 'test_user2',
            'password' => 'setup1Q',
        ]);

        $user3 = $this->_createUser([
            'db-id' => $db2->id,
            'login' => 'test_user3',
            'password' => 'setup1Q',
        ]);

        $dbUsers = static::$_client->database()->getAllUsers('db-id', $db1->id);
        $this->assertEquals(2, count($dbUsers));
        $this->assertEquals('test_user1', $dbUsers[0]->login);
        $this->assertEquals('test_user2', $dbUsers[1]->login);

        static::$_client->database()->deleteUser('id', $user1->id);
        static::$_client->database()->deleteUser('id', $user2->id);
        static::$_client->database()->deleteUser('id', $user3->id);
        static::$_client->database()->delete('id', $db1->id);
        static::$_client->database()->delete('id', $db2->id);
    }

    public function testDelete()
    {
        $database = $this->_createDatabase([
            'webspace-id' => static::$_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $result = static::$_client->database()->delete('id', $database->id);
        $this->assertTrue($result);
    }

    public function testDeleteUser()
    {
        $database = $this->_createDatabase([
            'webspace-id' => static::$_webspace->id,
            'name' => 'test1',
            'type' => 'mysql',
            'db-server-id' => 1
        ]);
        $user = $this->_createUser([
            'db-id' => $database->id,
            'login' => 'test_user1',
            'password' => 'setup1Q',
        ]);

        $result = static::$_client->database()->deleteUser('id', $user->id);
        $this->assertTrue($result);
        static::$_client->database()->delete('id', $database->id);
    }

    /**
     * @param array $params
     * @return \PleskX\Api\Struct\Database\Info
     */
    private function _createDatabase(array $params)
    {
        $database = static::$_client->database()->create($params);
        $this->assertInternalType('integer', $database->id);
        $this->assertGreaterThan(0, $database->id);
        return $database;
    }

    /**
     * @param array $params
     * @return \PleskX\Api\Struct\Database\UserInfo
     */
    private function _createUser(array $params)
    {
        $user = static::$_client->database()->createUser($params);
        $this->assertInternalType('integer', $user->id);
        $this->assertGreaterThan(0, $user->id);
        return $user;
    }
}