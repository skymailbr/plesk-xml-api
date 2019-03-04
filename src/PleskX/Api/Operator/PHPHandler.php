<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Struct\PHPHandler as Struct;

class PHPHandler extends \PleskX\Api\Operator
{

    /**
     * @param string $field
     * @param integer|string $value
     *
     * @return array|Struct\Info
     */
    public function get($field = null, $value = null)
    {
        $packet = $this->_client->getPacket();

        $filter = $packet->addChild('php-handler')->addChild('get')->addChild('filter');
        if ($field) {
            $filter->addChild($field, $value);
        }

        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL)->{'php-handler'}->get->result;

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
