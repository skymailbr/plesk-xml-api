<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\ServicePlan as Struct;

class ServicePlan extends \PleskX\Api\Operator
{

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\Data
     */
    public function get($field, $value)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('service-plan')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL)->{'service-plan'}->get->result;
        return new Struct\Data($response);
    }

    /**
     * @return array [Struct\Data]
     */
    public function getAll()
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('service-plan')->addChild('get');
        $getTag->addChild('filter');
        $getTag->addChild('owner-all');
        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL)->{'service-plan'}->get->result;
        $ret = [];
        foreach ($response as $f) {
            if (isset($f->id)) {
                $ret[] = new Struct\Data($f);
            }
        }
        return $ret;
    }
}
