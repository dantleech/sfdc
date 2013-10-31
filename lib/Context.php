<?php

class Context
{
    protected $fragments;

    public function add($format, $content)
    {
        $this->fragments[$format] = $content;
    }

    public function getFragments()
    {
        return $this->fragments;
    }
}
