<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Struct\SiteAlias as Struct;

class SiteAlias extends \PleskX\Api\Operator
{

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     * @throws \Exception
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
     * @throws \Exception
     */
    public function getAll($field, $value)
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('site-alias')->addChild('get')->addChild('filter')->addChild($field, $value);

        $response = $this->_client->request($packet, Client::RESPONSE_FULL);
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\GeneralInfo($xmlResult);
        }
        return $items;
    }

    /**
     * @param array $properties
     * @return Struct\Info
     * @throws \Exception
     */
    public function create($properties)
    {
        $response = $this->_process('create', $properties);
        return new Struct\Info($response);
    }

    /**
     * @param array $filter
     * @param array $properties
     * @return Struct\Info
     * @throws \Exception
     */
    public function update($filter, $settings)
    {
        $properties = [
            'filter' => $filter,
            'settings' => $settings
        ];
        $response = $this->_process('set', $properties);
        return new Struct\Info($response);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return bool
     * @throws \Exception
     */
    public function delete($field, $value)
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('site-alias')->addChild('delete')->addChild('filter')->addChild($field, $value);
        $response = $this->_client->request($packet);
        return 'ok' === (string)$response->status;
    }

    /**
     * @param string $filter
     * @param string|integer $valueFilter
     * @param string $newName
     * @param integer|string $value
     * @return bool
     * @throws \Exception
     */
    public function rename($filter, $valueFilter, $newName)
    {
        $packet = $this->_client->getPacket();
        $operator = $packet->addChild('site-alias')->addChild('rename');
        $operator->addChild($filter, $valueFilter);
        $operator->addChild('new_name', $newName);
        $response = $this->_client->request($packet);
        return 'ok' === (string)$response->status;
    }
}
