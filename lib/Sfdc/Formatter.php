<?php

namespace Sfdc;

abstract class Formatter
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    abstract public function format();
}
