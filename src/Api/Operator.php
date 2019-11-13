<?php
// Copyright 1999-2019. Plesk International GmbH.

namespace PleskX\Api;

class Operator
{

    /** @var string|null */
    protected $_wrapperTag = null;

    /** @var \PleskX\Api\Client */
    protected $_client;

    public function __construct($client)
    {
        $this->_client = $client;

        if (is_null($this->_wrapperTag)) {
            $classNameParts = explode('\\', get_class($this));
            $this->_wrapperTag = end($classNameParts);
            $this->_wrapperTag = strtolower(preg_replace('/([a-z])([A-Z])/', '\1-\2', $this->_wrapperTag));
        }
    }

    /**
     * @param string $field
     * @param integer|string $value
     * @param string $deleteMethodName
     * @return bool
     */
    protected function _delete($field, $value, $deleteMethodName = 'del')
    {
        $response = $this->request("$deleteMethodName.filter.$field=$value");
        return 'ok' === (string)$response->status;
    }

    /**
     * @param $command
     * @param array $properties
     * @param string $mode Client response mode
     * @return \PleskX\Api\XmlResponse
     * @throws \Exception
     */
    protected function _process($command, array $properties, $mode = Client::RESPONSE_SHORT)
    {
        $packet = $this->_client->getPacket();
        $info = $packet
            ->addChild($this->_wrapperTag)
            ->addChild($command);

        $set = function ($name, $value, \SimpleXMLElement $node) use (&$set, &$packet) {
            if (is_array($value)) {
                $node = $node->addChild($name);
                foreach ($value as $name => $v) {
                    $set($name, $v, $node);
                }
            } else {
                if (false !== strpos($value, '&')) {
                    $node->$name = $value;
                    return;
                }
                $node->addChild($name, $value);
            }
        };

        foreach ($properties as $name => $value) {
            $set($name, $value, $info);
        }

        return $this->_client->request($packet, $mode);
    }

    /**
     * Perform plain API request
     *
     * @param string|array $request
     * @param int $mode
     * @return XmlResponse
     */
    public function request($request, $mode = Client::RESPONSE_SHORT)
    {
        $wrapperTag = $this->_wrapperTag;

        if (is_array($request)) {
            $request = [$wrapperTag => $request];
        } else {
            if (preg_match('/^[a-z]/', $request)) {
                $request = "$wrapperTag.$request";
            } else {
                $request = "<$wrapperTag>$request</$wrapperTag>";
            }
        }
        return $this->_client->request($request, $mode);
    }

    /**
     * @param string $structClass
     * @param string $infoTag
     * @param string|null $field
     * @param integer|string|null $value
     * @param callable|null $filter
     * @return mixed
     * @throws \Exception
     */
    protected function _getItems($structClass, $infoTag, $field = null, $value = null, callable $filter = null)
    {
        $packet = $this->_client->getPacket();
        $getTag = $packet->addChild($this->_wrapperTag)->addChild('get');

        $filterTag = $getTag->addChild('filter');
        if (!is_null($field)) {
            $filterTag->addChild($field, $value);
        }

        $getTag->addChild('dataset')->addChild($infoTag);

        $response = $this->_client->request($packet, \PleskX\Api\Client::RESPONSE_FULL);

        $items = [];
        foreach ($response->xpath('//result') as $xmlResult) {
            if (!is_null($filter) && !$filter($xmlResult->data->$infoTag)) {
                continue;
            }
            $items[] = new $structClass($xmlResult->data->$infoTag);
        }

        return $items;
    }

}
