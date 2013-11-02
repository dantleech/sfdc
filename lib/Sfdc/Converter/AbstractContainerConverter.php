<?php

namespace Sfdc\Converter;

use Sfdc\Converter;

abstract class AbstractContainer extends Converter
{
    public function getScore()
    {
        $this->xpath->registerNamespace('services', 'http://symfony.com/schema/dic/services');
        $res = $this->query('/services:container/*[local-name()="services"]');

        if ($res->length) {
            return 2;
        }

        return 0;
    }

    public function getFragmentDescription()
    {
        return 'Symfony Service Container';
    }
}
