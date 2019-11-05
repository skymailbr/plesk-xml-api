<?php

// Copyright 1999-2015. Parallels IP Holdings GmbH.

namespace PleskX\Api;

abstract class Struct
{

    public function __set($property, $value)
    {
        throw new \Exception("Try to set an undeclared property '$property'.");
    }

    /**
     * Initialize list of scalar properties by response
     *
     * @param \SimpleXMLElement $apiResponse
     * @param array $properties
     * @throws \Exception
     */
    protected function initScalarProperties($apiResponse, array $properties)
    {

        foreach ($properties as $property) {
            if (is_array($property)) {
                $classPropertyName = current($property);
                $value = $apiResponse->{key($property)};
            } else {
                $classPropertyName = $this->underToCamel($property);
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
                if (class_exists($propertyType)) {
                    $value = new $propertyType($value);
                } else {
                    throw new \Exception("Unknown property type '$propertyType'.");
                }
            }

            $this->$classPropertyName = $value;
        }
    }

    /**
     * Convert underscore separated words into camel case
     *
     * @param string $under
     * @return string
     */
    protected function underToCamel($under)
    {
        $under = '_' . str_replace(['_','-'], ' ', strtolower($under));
        return ltrim(str_replace(' ', '', ucwords($under)), '_');
    }
}
