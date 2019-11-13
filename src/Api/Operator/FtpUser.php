<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.
namespace PleskX\Api\Operator;

use PleskX\Api\Client;
use PleskX\Api\Struct\FtpUser as Struct;

class FtpUser extends \PleskX\Api\Operator
{
    /**
     * Create FTP account
     *
     * @param array $properties
     * @return Struct\Info
     * @throws \Exception
     */
    public function create($properties)
    {
        return new Struct\Info($this->_process('add', $properties));
    }

    /**
     * Change FTP account
     *
     * @param string $field
     * @param integer|string $value
     * @param array $properties
     * @return Struct\Info
     * @throws \Exception
     */
    public function set($field, $value, $properties)
    {
        $properties = [
            'filter' => [
                $field => $value
            ],
            'values' => $properties
        ];

        $response = $this->_process('set', $properties, Client::RESPONSE_FULL);
        $xmlResult = $response->xpath('//result')[0];
        return new Struct\Info($xmlResult);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function delete($field, $value)
    {
        return $this->_delete($field, $value);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\Info
     */
    public function get($field, $value)
    {
        $items = $this->getAll($field, $value);
        return reset($items);
    }


    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\Info[]
     */
    public function getAll($field, $value)
    {
        $response = $this->request("get.filter.$field=$value");
        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            $items[] = new Struct\Info($xmlResult);
        }
        return $items;
    }

}