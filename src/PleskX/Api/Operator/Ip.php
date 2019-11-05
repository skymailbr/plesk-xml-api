<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Ip as Struct;

class Ip extends \PleskX\Api\Operator
{

    /**
     * @return Struct\Info[]
     */
    public function get()
    {
        $ips = [];
        $packet = $this->client->getPacket();
        $packet->addChild('ip')->addChild('get');
        $response = $this->client->request($packet);

        foreach ($response->addresses->ip_info as $ipInfo) {
            $ips[] = new Struct\Info($ipInfo);
        }

        return $ips;
    }
}
