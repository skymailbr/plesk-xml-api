<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Database as Struct;

class Database extends \PleskX\Api\Operator
{
    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        return new Struct\Info($this->_process('add-db', $properties));
    }

    /**
     * @param $properties
     * @return Struct\UserInfo
     */
    public function createUser($properties)
    {
        return new Struct\UserInfo($this->_process('add-db-user', $properties));
    }

    /**
     * @param array $properties
     * @return bool
     */
    public function updateUser(array $properties)
    {
        $response = $this->_process('set-db-user', $properties);
        return 'ok' === (string)$response->status;
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\Info
     */
    public function get($field, $value)
    {
        $items = $this->getAll($field, $value);
        return reset($items);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\Info[]
     */
    public function getAll($field, $value)
    {
        $response = $this->request("get-db.filter.$field=$value");
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\Info($xmlResult);
        }
        return $items;
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\UserInfo
     */
    public function getUser($field, $value)
    {
        $items = $this->getAllUsers($field, $value);
        return reset($items);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\UserInfo[]
     */
    public function getAllUsers($field, $value)
    {
        $response = $this->request("get-db-users.filter.$field=$value");
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\UserInfo($xmlResult);
        }
        return $items;
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function delete($field, $value)
    {
        return $this->_delete($field, $value, 'del-db');
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function deleteUser($field, $value)
    {
        return $this->_delete($field, $value, 'del-db-user');
    }
}
