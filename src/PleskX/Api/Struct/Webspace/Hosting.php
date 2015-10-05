<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class Hosting extends \PleskX\Api\Struct
{

    /** @var array **/
    public $types;

    public function __construct($apiResponse)
    {
        $this->_hostingScalarProperties($apiResponse, ['property']);
    }

    private function _hostingScalarProperties($apiResponse,$arrayElement)
    {
        $sxe = new \SimpleXmlIterator($apiResponse->asXML());
        for ($sxe->rewind(); $sxe->valid(); $sxe->next()) {
            $k = $sxe->key();
            $this->types[$k] = new \stdClass();
            if ( $sxe->hasChildren() ) {
                foreach ($sxe->getChildren() as $element => $value) {
                    if ( in_array($element, $arrayElement) ) {
                        $this->types[$k]->{parent::_underToCamel($value->name)} = $value->value;
                    } else {
                        $this->types[$k]->{parent::_underToCamel($element)} = $value;
                    }
                }
            }
        }
    }

}