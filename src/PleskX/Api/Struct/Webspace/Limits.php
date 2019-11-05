<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class Limits extends \PleskX\Api\Struct
{

    /** @var stdClass **/
    public $limit;
    /** @var string * */
    public $overuse;
    public function __construct($apiResponse)
    {
        $this->limitScalarProperties($apiResponse, ['limit']);
    }

    private function limitScalarProperties($apiResponse, array $arrayElement)
    {
        foreach ($arrayElement as $el) {
            $this->{$el} = new \stdClass();
        }
        $sxe = new \SimpleXmlIterator($apiResponse->asXML());
        for (
            $sxe->rewind(); $sxe->valid(); $sxe->next()
        ) {
            $k = $sxe->key();
            if (false !== in_array($k, $arrayElement)) {
                $this->{$k}->{parent::underToCamel($sxe->current()->name)} = $sxe->current()->value;
            } else {
                $this->{parent::underToCamel($k)} = $sxe->current();
            }
        }
    }
}
