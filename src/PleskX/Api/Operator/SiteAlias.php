<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\SiteAlias as Struct;

class SiteAlias extends \PleskX\Api\Operator
{

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('site-alias')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $ret = null;
        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL)->{'site-alias'}->{'get'}->result;
        if (in_array($field, ['id', 'name']) && isset($response->id)) {
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
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $properties = ['site-alias' => ['create' => $properties]];
        $packet = $this->client->genRequestXml($properties);
        $response = $this->client->request($packet);
        return new Struct\Info($response);
    }


    /**
     * @param array $filter
     * @param array $properties
     * @return Struct\Info
     */
    public function update($filter, $settings)
    {
        $properties = ['site-alias' => ['set' => ['filter' => $filter, 'settings' => $settings]]];
        $packet = $this->client->genRequestXml($properties);
        $response = $this->client->request($packet);
        return new Struct\Info($response);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function delete($field, $value)
    {
        $packet = $this->client->getPacket();
        $packet->addChild('site-alias')->addChild('delete')->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet);
        return 'ok' === (string)$response->status;
    }


    /**
     * @param string $filter
     * @param string|integer $valueFilter
     * @param string $newName
     * @param integer|string $value
     * @return bool
     */
    public function rename($filter, $valueFilter, $newName)
    {
        $packet = $this->client->getPacket();
        $operator = $packet->addChild('site-alias')->addChild('rename');
        $operator->addChild($filter, $valueFilter);
        $operator->addChild('new_name', $newName);
        $response = $this->client->request($packet);
        return 'ok' === (string)$response->status;
    }
}
