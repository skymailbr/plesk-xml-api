<?php
// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api;

abstract class StructData extends Struct
{

    /**
     * Initialize list of scalar properties by response data
     *
     * @param \SimpleXMLElement $apiResponse
     * @param array multilevel $properties
     * @throws \Exception
     */
    protected function _initScalarProperties($apiResponse, array $properties)
    {
        foreach ($properties as $property) {
            if (is_array($property)) {
                $classPropertyName = current($property);
                $value = $apiResponse->{key($property)};
            } else {
                $classPropertyName = $this->_underToCamel(str_replace('-', '_', $property));
                $value = $apiResponse->$property;
            }

            $reflectionProperty = new \ReflectionProperty($this, $classPropertyName);
            $docBlock = $reflectionProperty->getDocComment();
            $propertyType = trim(preg_replace('/^.+ @var ([^\*]+) .+$/', '\1', $docBlock));

            if ('string' == $propertyType) {
                $value = (string)$value;
            } else if ('integer' == $propertyType) {
                $value = (int)$value;
            } else if ('boolean' == $propertyType) {
                $value = in_array((string)$value, ['true', 'on', 'enabled']);
            } else {
                if ( class_exists($propertyType) ) {
                    $value = new $property($value);
                }
                throw new \Exception("Unknown property type '$propertyType'.");
            }

            $this->$classPropertyName = $value;
        }
    }


}