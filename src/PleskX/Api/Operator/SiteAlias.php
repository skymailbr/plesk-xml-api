<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\SiteAlias as Struct;

class SiteAlias extends \PleskX\Api\Operator
{

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild('site-alias')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $response = $this->_client->request($packet);
        return new Struct\GeneralInfo($response);
    }


    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $properties = ['site-alias' => ['create' => $properties]];
        $packet = $this->_client->genRequestXml( $properties );
        $response = $this->_client->request($packet);
        return new Struct\Info($response);
    }


    /**
     * @param array $filter
     * @param array $properties
     * @return Struct\Info
     */
    public function update($filter, $settings)
    {
        $properties = ['site-alias'=>['set'=>['filter'=>$filter,'settings'=>$settings]]];
        $packet = $this->_client->genRequestXml( $properties );
        $response = $this->_client->request($packet);
        return new Struct\Info($response);
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @return bool
     */
    public function delete($field, $value)
    {
        $packet = $this->_client->getPacket();
        $packet->addChild('site-alias')->addChild('delete')->addChild('filter')->addChild($field, $value);
        $response = $this->_client->request($packet);
        return 'ok' === (string)$response->status;
    }


    /**
     * @param string $filter
     * @param string|integer $valueFilter
     * @param string $newName
     * @param integer|string $value
     * @return bool
     */
    public function rename($filter, $valueFilter,  $newName)
    {
        $packet = $this->_client->getPacket();
        $operator = $packet->addChild('site-alias')->addChild('rename');
        $operator->addChild( $filter, $valueFilter );
        $operator->addChild( 'new_name', $newName );
        $response = $this->_client->request($packet);
        return 'ok' === (string)$response->status;
    }

}