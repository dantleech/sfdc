<?php

namespace Sfdc\Converter;

use Sfdc\Context;
use Sfdc\Converter;

class ContainerPhpConverter extends Converter
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

    public function getFormat()
    {
        return 'php';
    }

    public function getFragmentDescription()
    {
        return 'Symfony Service Container';
    }

    public function convert()
    {
        $lines = array();
        $lines[] = 'use Symfony\Component\DependencyInjection\Reference;';
        $lines[] = '// ...';
        $lines[] = '';

        /** @var $dom \DOMDocument */
        $dom = $this->getDom();

        $this->xpath->registerNamespace('s', 'http://symfony.com/schema/dic/services');

        foreach ($this->query('/s:container/s:services/s:service') as $nodeService) {
            $lines[] = sprintf('$container->register(\'%s\', \'%s\')', 
                $nodeService->getAttribute('id'),
                $nodeService->getAttribute('class')
            );

            $this->getArgumentsForNode($lines, $nodeService, 2);

            $tags = array();
            foreach ($this->query('./s:tag', $nodeService) as $nodeTag) {
                $tagAttrs = array();
                foreach ($nodeTag->attributes as $nodeAttr) {
                    $tagAttrs[] = sprintf('\'%s\' => \'%s\'', $nodeAttr->name, $nodeAttr->value);
                }

                $lines[] = sprintf('  ->addTag(\'%s\', array(%s)',
                    $nodeTag->getAttribute('name'),
                    implode(', ', $tagAttrs)
                );
            }

            $calls = array();
            foreach ($this->query('./s:call', $nodeService) as $nodeCall) {
                $lines[] = sprintf('  ->addMethodCall(\'%s\', array(',
                    $nodeCall->getAttribute('method')
                );
                $this->getArgumentsForNode($lines, $nodeCall, 6, false);
                $lines[] = '  ))';
            }

            $lines[] = ';';
        }

        return implode("\n", $lines);
    }

    protected function getArgumentsForNode(&$lines, \DOMNode $node, $indent = 0, $fluid = true)
    {
        foreach ($this->query('./s:argument', $node) as $nodeArg) {
            if ($nodeArg->hasAttribute('type')) {
                $type = $nodeArg->getAttribute('type');
                $id = $nodeArg->getAttribute('id');

                if ($type == 'service') {
                    $line = sprintf('new Reference(\'%s\')', $id);
                }
            } else {
                $line = sprintf('\'%s\'', $nodeArg->nodeValue);
            }

            if (true === $fluid) {
                $line = sprintf('->addArgument(%s)', $line);
            } else {
                $line .= ',';
            }

            $line = str_repeat(' ', $indent).$line;
            $lines[] = $line;
        }
    }
} 
