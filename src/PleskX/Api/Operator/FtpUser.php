<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\FtpUser as Struct;

class FtpUser extends \PleskX\Api\Operator
{


    /**
     * Create FTP account
     *
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $properties = ['ftp-user' => ['add' => $properties]];
        $packet = $this->client->genRequestXml($properties);
        $response = $this->client->request($packet);
        return new Struct\Info($response);
    }

    /**
     * Change FTP account
     *
     * @param string $field
     * @param integer|string $value
     * @param array $properties
     * @return Struct\Info
     */
    public function set($field, $value, $properties)
    {
        $properties = ['ftp-user' => ['set' => ['filter' => [ $field => $value ], 'values' => $properties ]]];
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
        $packet->addChild('ftp-user')->addChild('del')->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet);
        return 'ok' === (string)$response->status;
    }

    /**
     * Get FTP accounts
     *
     * @param string $field
     * @param integer|string $value
     * @return mixed Struct\GeneralInfo|Array
     */
    public function get($field, $value)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('ftp-user')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL)->{'ftp-user'}->get->result;
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
