<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;
use PleskX\Api\Struct\Webspace as Struct;

class Webspace extends \PleskX\Api\Operator
{

    public function getPermissionDescriptor()
    {
        $response = $this->request('get-permission-descriptor.filter');
        return new Struct\PermissionDescriptor($response);
    }

    public function getLimitDescriptor()
    {
        $response = $this->request('get-limit-descriptor.filter');
        return new Struct\LimitDescriptor($response);
    }

    public function getPhysicalHostingDescriptor()
    {
        $response = $this->request('get-physical-hosting-descriptor.filter');
        return new Struct\PhysicalHostingDescriptor($response);
    }

    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $properties = ['webspace' => ['add' => $properties]];
        $packet = $this->_client->genRequestXml($properties);
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
        $packet->addChild('webspace')->addChild('del')->addChild('filter')->addChild($field, $value);
        $response = $this->_client->request($packet);
        return 'ok' === (string)$response->status;
    }


    /**
     * Get gen_info of webspace [name,guid]
     *
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild('webspace')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $getTag->addChild('dataset')->addChild('gen_info');
        $response = $this->_client->request($packet);
        return new Struct\GeneralInfo($response->data->gen_info);
    }

    
    /**
     * Get Data of webspace 
     *
     * @param string $field
     * @param integer|string $value
     * @return Struct\Data
     */
    public function getData($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild('webspace')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $dataset = $getTag->addChild('dataset');
        $dataset->addChild('gen_info');
        $dataset->addChild('hosting');
        $dataset->addChild('limits');
        $dataset->addChild('stat');
        $dataset->addChild('prefs');
        $dataset->addChild('disk_usage');
        $dataset->addChild('performance');
        $response = $this->_client->request($packet);
        return new Struct\Data($response->data);
    }


}
