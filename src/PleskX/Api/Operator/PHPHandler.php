<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Operator;
use PleskX\Api\Struct\PHPHandler as Struct;

class PHPHandler extends Operator
{

    /**
     * @param string $field
     * @param integer|string $value
     *
     * @return array|Struct\Info
     */
    public function get($field = null, $value = null)
    {
        $packet = $this->client->getPacket();
        $filter = $packet->addChild('php-handler')->addChild('get')->addChild('filter');
        if ($field) {
            $filter->addChild($field, $value);
        }

        $response = $this->client->request($packet, Client::RESPONSE_FULL)->{'php-handler'}->get->result;
        $ret = null;
        if ($field == 'id' && isset($response->id)) {
            $ret = new Struct\Info($response);
        } else {
            $ret = [];
            foreach ($response as $f) {
                if (isset($f->id)) {
                    $ret[] = new Struct\Info($f);
                }
            }
        }

        return $ret;
    }
}
