<?php

namespace Sfdc;

use Sfdc\Context;

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

        $converters = array();
        $context = new Context;

        $res = opendir(__DIR__.'/Converter');


        while ($filename = readdir($res)) {
            if (preg_match('&(.*Converter).php$&', $filename, $matches)) {
                $fqn = sprintf(__NAMESPACE__.'\\Converter\\'.$matches[1]);
                $converters[] = new $fqn($dom);
            }
        }

        $winner = array(
            'score' => 0,
            'obj' => null
        );

        foreach ($converters as $converter) {
            $score = $converter->getScore();

            if ($score > $winner['score']) {
                $winner = array(
                    'score' => $converter->getScore(),
                    'obj' => $converter
                );
                continue;
            }
        }

        if (!$winner) {
            throw new \Exception('Could not find converter candidate for XML document');
        }

        $arrayRepresentation = $winner['obj']->convert();

        $yaml = \Symfony\Component\Yaml\Yaml::dump($arrayRepresentation);
        $context->add('yaml', $yaml);


        return $context;
    }
}
