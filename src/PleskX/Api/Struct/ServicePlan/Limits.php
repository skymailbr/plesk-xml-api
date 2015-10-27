<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\ServicePlan;

class Limits extends \PleskX\Api\Struct
{

    /** @var stdClass **/
    public $limit;

    /** @var string **/
    public $overuse;

    public function __construct($apiResponse)
    {
        $this->_limitScalarProperties($apiResponse,['limit']);
    }

    private function _limitScalarProperties($apiResponse, array $arrayElement)
    {
        foreach( $arrayElement as $el ) {
            $this->{$el} = new \stdClass();
        }
        $sxe = new \SimpleXmlIterator($apiResponse->asXML());
        for ($sxe->rewind(); $sxe->valid(); $sxe->next()) {
            $k = $sxe->key();
            if ( FALSE !== in_array($k, $arrayElement) ) {
                $this->{$k}->{parent::_underToCamel($sxe->current()->name)} = $sxe->current()->value;
            } else $this->{parent::_underToCamel($k)} = $sxe->current();
        }
    }

}