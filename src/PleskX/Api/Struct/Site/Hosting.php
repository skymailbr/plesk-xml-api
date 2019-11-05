<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Site;

class Hosting extends \PleskX\Api\Struct
{

    /** @var array **/
    public $types;
    public function __construct($apiResponse)
    {
        $this->hostingScalarProperties($apiResponse, ['property']);
    }

    private function hostingScalarProperties($apiResponse, array $arrayElement)
    {
        $xml = $apiResponse->asXML();
        $sxe = new \SimpleXmlIterator($xml);
        for (
            $sxe->rewind(); $sxe->valid(); $sxe->next()
        ) {
            $k = $sxe->key();
            $this->types[$k] = new \stdClass();
            if ($sxe->hasChildren()) {
                foreach ($sxe->getChildren() as $element => $value) {
                    if (false !== in_array($element, $arrayElement)) {
                        $this->types[$k]->{parent::underToCamel($value->name)} = $value->value;
                    } else {
                        $this->types[$k]->{parent::underToCamel($element)} = $value;
                    }
                }
            }
        }
    }
}
