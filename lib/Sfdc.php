<?php

/**
 * Sfdc
 *
 * Symfony Fragment Documentation Converter
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Sfdc
{
    protected $xmlFragment;

    public function __construct($xmlFragment)
    {
        $this->xmlFragment = $xmlFragment;
    }

    public function run()
    {
        $dom = new \DOMDocument(1.0);
        $dom->loadXml($this->xmlFragment);

        $processors = array();
        $processors[] = new Proc\SfContainerYaml($dom);
        $context = new \Context;

        foreach ($processors as $processor) {
            if ($processor->canProcess()) {
                $processor->process($context);
            }
        }

        return $context;
    }
}
