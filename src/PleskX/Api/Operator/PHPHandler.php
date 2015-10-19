<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\PHPHandler as Struct;

class PHPHandler extends \PleskX\Api\Operator
{

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\Info
     */
    public function get($field = null, $value = null)
    {
        $ips = [];
        $packet = $this->_client->getPacket();
        $filter = $packet->addChild('php-handler')->addChild('get')->addChild('filter');
        if ($field) $filter->addChild($field, $value);
        $response = $this->_client->request($packet);
        return new Struct\Info($response);
    }

}
