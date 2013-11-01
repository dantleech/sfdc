<?php

namespace Sfdc;

class Context
{
    protected $fragments = array();

    public function add($format, $content)
    {
        $this->fragments[$format] = $content;
    }

    public function getFragments()
    {
        return $this->fragments;
    }
}
