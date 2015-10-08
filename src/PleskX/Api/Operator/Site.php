<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Site as Struct;

class Site extends \PleskX\Api\Operator
{

    /**
     * @param string $field
     * @param integer|string $value
     * @return Struct\GeneralInfo
     */
    public function get($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild('site')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $getTag->addChild('dataset')->addChild('gen_info');
        $response = $this->_client->request($packet);
        return new Struct\GeneralInfo($response->data->gen_info);
    }

    /**
     * Get Data of site 
     *
     * @param string $field
     * @param integer|string $value
     * @return mixed Array|Struct\Data 
     */
    public function getData($field, $value)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild('site')->addChild('get');
        $getTag->addChild('filter')->addChild($field, $value);
        $dataset = $getTag->addChild('dataset');
        $dataset->addChild('gen_info');
        $dataset->addChild('hosting');
        $dataset->addChild('stat');
        $dataset->addChild('prefs');
        $dataset->addChild('disk_usage');
        $ret = NULL;
        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL)->{'site'}->{'get'}->result;
        if ( in_array($field,['id','name','guid']) && isset( $response->id ) ) {
            $ret = new Struct\Data($response->data);
        } else {
            $ret = [];
            foreach ($response as $f) {
                if ( isset( $f->id ) ) $ret[] = new Struct\Data($f->data);
            }
        }
        return $ret;
    }

    /**
     * @param array $properties
     * @return Struct\Info
     */
    public function create($properties)
    {
        $properties = ['site' => ['add' => $properties]];
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
        $packet->addChild('site')->addChild('del')->addChild('filter')->addChild($field, $value);
        $response = $this->_client->request($packet);
        return 'ok' === (string)$response->status;
    }

}
