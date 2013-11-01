<?php

namespace Sfdc\Converter;

use Sfdc\Context;
use Sfdc\Converter;

class ContainerConverter extends Converter
{
    public function getScore()
    {
        $this->xpath->registerNamespace('services', 'http://symfony.com/schema/dic/services');
        $res = $this->query('/services:container');

        if ($res) {
            return 1;
        }

        return 0;
    }

    public function convert()
    {
        $data = array();

        /** @var $dom \DOMDocument */
        $dom = $this->getDom();

        $this->xpath->registerNamespace('services', 'http://symfony.com/schema/dic/services');

        foreach ($this->query('/services:container') as $services) {
            foreach ($this->xpath->query('./*[local-name()="config"]/*', $services) as $node) {
                $data[$node->prefix] = array();

                $attrs = array();
                foreach ($node->attributes as $attribute) {
                    $attrs[$attribute->name] = $attribute->value;
                }

                if ($attrs) {
                    $data[$node->prefix][$node->localName] = $attrs;
                }
            }
        }


        return $data;
    }
}
