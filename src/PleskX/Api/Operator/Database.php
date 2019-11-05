<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Database as Struct;

class Database extends \PleskX\Api\Operator
{

    /**
     * Create database
     *
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $properties = ['database' => ['add-db' => $properties]];
        $packet = $this->client->genRequestXml($properties);
        $response = $this->client->request($packet);
        return new Struct\Info($response);
    }

    /**
     * Delete Database
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function delete($field, $value)
    {
        $packet = $this->client->getPacket();
        $packet->addChild('database')->addChild('del-db')->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet);
        return 'ok' === (string)$response->status;
    }

    /**
     * Get database
     *
     * @param string $field
     * @param integer|string $value
     * @return mixed Struct\GeneralInfo|Array
     */
    public function get($field, $value)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('database')->addChild('get-db');
        $getTag->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet,
            \PleskX\Api\Client::RESPONSE_FULL)->{'database'}->{'get-db'}->result;
        $ret = null;
        if ($field == 'id' && isset($response->id)) {
            $ret = new Struct\GeneralInfo($response);
        } else {
            $ret = [];
            foreach ($response as $f) {
                if (isset($f->id)) {
                    $ret[] = new Struct\GeneralInfo($f);
                }
            }
        }
        return $ret;
    }


    /**
     * Create database user
     *
     * @param array $properties
     * @return Struct\InfoUser
     */
    public function createUser($properties)
    {
        $properties = ['database' => ['add-db-user' => $properties]];
        $packet = $this->client->genRequestXml($properties);
        $response = $this->client->request($packet);
        return new Struct\InfoUser($response);
    }

    /**
     * Change database user
     *
     * @param integer $id The database user id
     * @param array $properties
     * @return Struct\InfoUser
     */
    public function setUser($id, $properties)
    {
        $properties = ['database' => ['set-db-user' => array_merge(['id' => $id], $properties)]];
        $packet = $this->client->genRequestXml($properties);
        $response = $this->client->request($packet);
        return 'ok' === (string)$response->status;
    }


    /**
     * Delete Database user
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function deleteUser($field, $value)
    {
        $packet = $this->client->getPacket();
        $packet->addChild('database')->addChild('del-db-user')->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet);
        return 'ok' === (string)$response->status;
    }

    /**
     * Get database user
     *
     * @param string $field
     * @param integer|string $value
     * @return mixed Struct\GeneralInfoUser|Array
     */
    public function getUser($field, $value)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('database')->addChild('get-db-users');
        $getTag->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet,
            \PleskX\Api\Client::RESPONSE_FULL)->{'database'}->{'get-db-users'}->result;
        $ret = null;
        if ($field == 'id' && isset($response->id)) {
            $ret = new Struct\GeneralInfoUser($response);
        } else {
            $ret = [];
            foreach ($response as $f) {
                if (isset($f->id)) {
                    $ret[] = new Struct\GeneralInfoUser($f);
                }
            }
        }
        return $ret;
    }
}
