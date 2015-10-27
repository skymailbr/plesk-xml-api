<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\ServicePlan as Struct;

class ServicePlan extends \PleskX\Api\Operator
{

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild('service-plan')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL)->{'service-plan'}->get->result;
        return new Struct\Info($response);
    }

    public function getAll()
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild('service-plan')->addChild('get');
        $getTag->addChild('filter');
        $getTag->addChild('owner-all');
        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL)->{'service-plan'}->get->result;
        $ret = [];
        foreach ($response as $f) {
            if ( isset( $f->id ) ) $ret[] = new Struct\Info($f);
        }
        return $ret;
    }

}
