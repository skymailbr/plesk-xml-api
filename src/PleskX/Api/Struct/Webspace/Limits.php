<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api\Struct\Webspace;

class Limits extends \PleskX\Api\Struct
{

    /** @var stdObject **/
    public $limits;

    /** @var string **/
    public $overuse;

    public function __construct($apiResponse)
    {
        $this->_limitScalarProperties($apiResponse);
    }

    private function _limitScalarProperties( $apiResponse )
    {
        foreach ($apiResponse as $attr => $obj) {
            if (is_array($obj)) {
                foreach ($obj as $key => $v) {
                    $this->{$attr}->{parent::_underToCamel($v->name)} = $v->value;
                }
            } else $this->{$this->_underToCamel($attr)} = $obj;
        }
    }

}