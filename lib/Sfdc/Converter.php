<?php

namespace Sfdc;

abstract class Converter
{
    protected $dom;
    protected $xpath;

    public function __construct(\DOMDocument $dom)
    {
        $this->dom = $dom;
        $this->xpath = new \DOMXPath($dom);
    }

    abstract public function getScore();

    abstract public function convert();

    abstract public function getFragmentDescription();

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

    abstract public function getFormat();
}
