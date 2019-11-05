<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Operator;

use PleskX\Api\Struct\Server as Struct;

class Server extends \PleskX\Api\Operator
{

    /**
     * @return array
     */
    public function getProtos()
    {
        $packet = $this->client->getPacket();
        $packet->addChild('server')->addChild('get_protos');
        $response = $this->client->request($packet);

        return (array)$response->protos->proto;
    }

    public function getGeneralInfo()
    {
        return new Struct\GeneralInfo($this->getInfo('gen_info'));
    }

    public function getPreferences()
    {
        return new Struct\Preferences($this->getInfo('prefs'));
    }

    public function getAdmin()
    {
        return new Struct\Admin($this->getInfo('admin'));
    }

    /**
     * @return array
     */
    public function getKeyInfo()
    {
        $keyInfo = [];
        $keyInfoXml = $this->getInfo('key');

        foreach ($keyInfoXml->property as $property) {
            $keyInfo[(string)$property->name] = (string)$property->value;
        }

        return $keyInfo;
    }

    /**
     * @return array
     */
    public function getComponents()
    {
        $components = [];
        $componentsXml = $this->getInfo('components');

        foreach ($componentsXml->component as $component) {
            $components[(string)$component->name] = (string)$component->version;
        }

        return $components;
    }

    /**
     * @return array
     */
    public function getServiceStates()
    {
        $states = [];
        $statesXml = $this->getInfo('services_state');

        foreach ($statesXml->srv as $service) {
            $states[(string)$service->id] = [
                'id' => (string)$service->id,
                'title' => (string)$service->title,
                'state' => (string)$service->state,
            ];
        }

        return $states;
    }

    public function getSessionPreferences()
    {
        return new Struct\SessionPreferences($this->getInfo('session_setup'));
    }

    /**
     * @return array
     */
    public function getShells()
    {
        $shells = [];
        $shellsXml = $this->getInfo('shells');

        foreach ($shellsXml->shell as $shell) {
            $shells[(string)$shell->name] = (string)$shell->path;
        }

        return $shells;
    }

    /**
     * @return array
     */
    public function getNetworkInterfaces()
    {
        $interfacesXml = $this->getInfo('interfaces');
        return (array)$interfacesXml->interface;
    }

    public function getStatistics()
    {
        return new Struct\Statistics($this->getInfo('stat'));
    }

    /**
     * @return array
     */
    public function getSiteIsolationConfig()
    {
        $config = [];
        $configXml = $this->getInfo('site-isolation-config');

        foreach ($configXml->property as $property) {
            $config[(string)$property->name] = (string)$property->value;
        }

        return $config;
    }

    public function getUpdatesInfo()
    {
        return new Struct\UpdatesInfo($this->getInfo('updates'));
    }

    /**
     * @param string $login
     * @param string $clientIp
     * @return string
     */
    public function createSession($login, $clientIp)
    {
        $packet = $this->client->getPacket();
        $sessionNode = $packet->addChild('server')->addChild('create_session');
        $sessionNode->addChild('login', $login);
        $dataNode = $sessionNode->addChild('data');
        $dataNode->addChild('user_ip', base64_encode($clientIp));
        $dataNode->addChild('source_server');
        $response = $this->client->request($packet);

        return (string)$response->id;
    }

    /**
     * @param string $operation
     * @return \SimpleXMLElement
     */
    private function getInfo($operation)
    {
        $packet = $this->client->getPacket();
        $packet->addChild('server')->addChild('get')->addChild($operation);
        $response = $this->client->request($packet);

        return $response->$operation;
    }
}
