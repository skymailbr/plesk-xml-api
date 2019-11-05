<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api;

class Operator
{

    /** @var string|null */
    protected $_wrapperTag = null;

    /** @var \PleskX\Api\Client */
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
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

        if (is_null($wrapperTag)) {
            $classNameParts = explode('\\', get_class($this));
            $wrapperTag = end($classNameParts);
            $wrapperTag = strtolower(preg_replace('/([a-z])([A-Z])/', '\1-\2', $wrapperTag));
        }

        if (is_array($request)) {
            $request = [$wrapperTag => $request];
        } else if (preg_match('/^[a-z]/', $request)) {
            $request = "$wrapperTag.$request";
        } else {
            $request = "<$wrapperTag>$request</$wrapperTag>";
        }

        return $this->client->request($request, $mode);
    }
}
