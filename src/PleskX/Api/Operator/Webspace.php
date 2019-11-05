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
        $packet->addChild('webspace')->addChild('del')->addChild('filter')->addChild($field, $value);
        $response = $this->client->request($packet);
        return 'ok' === (string)$response->status;
    }


    /**
     * Change Webspace
     *
     * @param string $field
     * @param integer|string $value
     * @param array $properties
     * @return Struct\Info
     */
    public function set($field, $value, $properties)
    {
        $properties = ['webspace' => ['set' => ['filter' => [ $field => $value ], 'values' => $properties ]]];
        $packet = $this->client->genRequestXml($properties);
        $response = $this->client->request($packet);
        return new Struct\Info($response);
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
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('webspace')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $getTag->addChild('dataset')->addChild('gen_info');
        $response = $this->client->request($packet);
        return new Struct\GeneralInfo($response->data->gen_info);
    }
    
    /**
     * Get Data of webspace
     *
     * @param string $field optional
     * @param integer|string $value optional
     * @return mixed Struct\Data|Array
     */
    public function getData($field = null, $value = null)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('webspace')->addChild('get');
        $g = $getTag->addChild('filter');
        if ($field) {
            $g->addChild($field, $value);
        }
        $dataset = $getTag->addChild('dataset');
        $dataset->addChild('gen_info');
        $dataset->addChild('hosting');
        $dataset->addChild('limits');
        $dataset->addChild('stat');
        $dataset->addChild('prefs');
        $dataset->addChild('disk_usage');
        $dataset->addChild('performance');
        $dataset->addChild('subscriptions');
        $response = $this->client->request($packet, \PleskX\Api\Client::RESPONSE_FULL)->{'webspace'}->{'get'}->result;
        $ret = null;
        if (in_array($field, ['id', 'name']) && isset($response->id)) {
            $ret = new Struct\Data($response);
        } else {
            $ret = [];
            foreach ($response as $f) {
                if (isset($f->id)) {
                    $ret[] = new Struct\Data($f);
                }
            }
        }
        return $ret;
    }

    /**
     * Get Traffic of webspace
     *
     * @param string $field
     * @param integer|string $value
     * @param \DateTime $sinceDate optional
     * @param \DateTime $toDate optional
     * @return Struct\Data
     */
    public function getTraffic($field, $value, $sinceDate = null, $toDate = null)
    {
        $packet = $this->client->getPacket();
        $getTag = $packet->addChild('webspace')->addChild('get_traffic');
        $getTag->addChild('filter')->addChild($field, $value);
        if ($sinceDate) {
            $getTag->addChild('since_date', $sinceDate->format('Y-m-d'));
        }
        if ($toDate) {
            $getTag->addChild('to_date', $toDate->format('Y-m-d'));
        }
        $response = $this->client->request($packet);
        return new Struct\Traffic($response->traffic);
    }
}
