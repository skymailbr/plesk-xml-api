<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Reseller as Struct;

class Reseller extends \PleskX\Api\Operator
{

    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $packet = $this->client->getPacket();
        $info = $packet->addChild('reseller')->addChild('add')->addChild('gen-info');

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
        $packet->addChild('reseller')->addChild('del')->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet);
        return 'ok' === (string)$response->status;
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('reseller')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $getTag->addChild('dataset')->addChild('gen-info');
        $response = $this->client->request($packet);
        return new Struct\GeneralInfo($response->data->{'gen-info'});
    }
}
