<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\DatabaseServer as Struct;

class DatabaseServer extends \PleskX\Api\Operator
{

    protected $_wrapperTag = 'db_server';

    /**
     * @return array
     */
    public function getSupportedTypes()
    {
        $response = $this->request('get-supported-types');
        return (array)$response->type;
    }


    /**
     * Get database
     *
     * @param string $field
     * @param integer|string $value
     * @return mixed Struct\GeneralInfo|Array
     */
    public function get($field = null, $value = null)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get');
        $filter = $getTag->addChild('filter');
        if ($field && $value) {
            $filter->addChild($field, $value);
        }
        $response = $this->client->request($packet,
            \PleskX\Api\Client::RESPONSE_FULL)->{$this->_wrapperTag}->{'get'}->result;
        $ret = null;
        if ($field == 'id' && isset($response->id)) {
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
}
