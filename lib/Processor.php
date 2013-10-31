<?php

class Processor
{
    protected $dom;
    protected $xpath;

    public function __construct(\DOMDocument $dom)
    {
        $this->dom = $dom;
        $this->xpath = new \DOMXPath($dom);
    }

    public function getDom()
    {
        return $this->dom;
    }

    /**
     * @return \DOMXPath
     */
    public function query($xpath, $context = null)
    {
        return $this->xpath->query($xpath, $context);
    }
}
