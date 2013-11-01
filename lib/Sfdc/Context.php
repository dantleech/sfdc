<?php

namespace Sfdc;

class Context
{
    protected $fragments = array();

    public function add($format, $content, $description)
    {
        $this->fragments[$format] = array(
            'content' => $content,
            'description' => $description,
        );
    }

    public function getFragments()
    {
        return $this->fragments;
    }
}
