<?php

namespace Proc;

class SfContainerYaml extends \Processor
{
    public function canProcess()
    {
        $res = $this->query('/container');
        if ($res) {
            return true;
        }

        return false;
    }

    public function process(\Context $context)
    {
        $yaml = array();

        /** @var $dom \DOMDocument */
        $dom = $this->getDom();

        $this->xpath->registerNamespace('services', 'http://symfony.com/schema/dic/services');
        $this->xpath->registerNamespace('framework', 'http://symfony.com/schema/dic/symfony');

        foreach ($this->query('/services:container') as $services) {
            foreach ($this->xpath->query('.//framework:config/framework:*', $services) as $node) {
                $yaml['framework'] = array();
                $attrs = array();
                foreach ($node->attributes as $attribute) {
                    $attrs[$attribute->name] = "'".$attribute->value."'";
                }

                if ($attrs) {
                    $yaml[$node->prefix][$node->localName] = implode(', ', $attrs);
                }
            }
        }

        $lines = $this->arrayToYaml($yaml);
        $out = implode($lines, "\n");

        $context->add('yaml', $out);
    }

    protected function arrayToYaml($array, $lines = array(), $level = 0)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $lines[] = $key.':';
                $lines = $this->arrayToYaml($value, $lines, ++$level);
            } else {
                $lines[] = str_repeat('  ', $level).$key.': '.$value;
            }
        }

        return $lines;
    }
}
