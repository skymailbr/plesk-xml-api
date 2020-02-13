<?php

namespace PleskX\Api;

class SimpleXMLElementExtended extends \SimpleXMLElement
{

    /**
     * @param string $name
     * @param string|null $value
     * @return \SimpleXMLElement
     */
    public function addChildWithCDATA(string $name, string $value = null): \SimpleXMLElement
    {
        $child = $this->addChild($name);

        if (null !== $child) {
            $node = dom_import_simplexml($child);
            $no = $node->ownerDocument;
            $node->appendChild($no->createCDATASection($value));
        }

        return $child;
    }
}
