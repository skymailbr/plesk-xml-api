<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Customer as Struct;

class Customer extends \PleskX\Api\Operator
{

    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild('customer')->addChild('add')->addChild('gen_info');

        foreach ($properties as $name => $value) {
            $info->addChild($name, $value);
        }

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
        $packet->addChild('customer')->addChild('del')->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet);
        return 'ok' === (string)$response->status;
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field = null, $value = null)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('customer')->addChild('get');
        $f = $getTag->addChild('filter');
        if ($field) {
            $f->addChild($field, $value);
        }
        $getTag->addChild('dataset')->addChild('gen_info');
        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL)->{'customer'}->get->result;
        if ($field) {
            return new Struct\GeneralInfo($response);
        } else {
            $ret = [];
            foreach ($response as $f) {
                if (isset($f->id)) {
                    $ret[] = new Struct\GeneralInfo($f);
                }
            }
            return $ret;
        }
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return array[Struct\Domain]
     */
    public function getDomainList($field, $value)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('customer')->addChild('get-domain-list');
        $getTag->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet)->domains->domain;
        $ret = [];
        foreach ($response as $f) {
            if (isset($f->id)) {
                $ret[] = new Struct\Domain($f);
            }
        }
        return $ret;
    }
}
