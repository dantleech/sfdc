<?php

namespace Sfdc\Converter;

class ContainerYamlConverter extends AbstractContainerConverter
{
    public function convert()
    {
        $data = array();

        /** @var $dom \DOMDocument */
        $dom = $this->getDom();

        $this->xpath->registerNamespace('s', 'http://symfony.com/schema/dic/services');

        foreach ($this->query('/s:container/s:services/s:service') as $nodeService) {
            $serviceData = array(
                'class' => $nodeService->getAttribute('class')
            );

            $args = $this->getArgumentsForNode($nodeService);

            if ($args) {
                $serviceData['arguments'] = $args;
            }

            $tags = array();
            foreach ($this->query('./s:tag', $nodeService) as $nodeTag) {
                $tag = array();
                foreach ($nodeTag->attributes as $nodeAttr) {
                    $tag[$nodeAttr->name] = $nodeAttr->value;
                }
                $tags[] = $tag;
            }

            if ($tags) {
                $serviceData['tags'] = $tags;
            }

            $calls = array();
            foreach ($this->query('./s:call', $nodeService) as $nodeCall) {
                $args = $this->getArgumentsForNode($nodeCall);
                $call = array(
                    $nodeCall->getAttribute('method'),
                    $args,
                );

                $calls[] = $call;
            }

            if ($calls) {
                $serviceData['calls'] = $calls;
            }

            $data[$nodeService->getAttribute('id')] = $serviceData;
        }

        $data = array('services' => $data);

        $yaml = \Symfony\Component\Yaml\Yaml::dump($data, 4);

        return $yaml;
    }

    protected function getArgumentsForNode(\DOMNode $node)
    {
        $args = array();
        foreach ($this->query('./s:argument', $node) as $nodeArg) {
            if ($nodeArg->hasAttribute('type')) {
                $type = $nodeArg->getAttribute('type');
                if ($type == 'service') {
                    $id = $nodeArg->getAttribute('id');
                    $args[] = '@'.$id;
                }
                continue;
            }

            $args[] = $nodeArg->nodeValue;
        }

        return $args;
    }

    public function getFormat()
    {
        return 'yaml';
    }
}
