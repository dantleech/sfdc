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
        $context = new Context();

        $res = opendir(__DIR__.'/Converter');

        while ($filename = readdir($res)) {
            if (preg_match('&(.*Converter).php$&', $filename, $matches)) {
                $fqn = sprintf(__NAMESPACE__.'\\Converter\\'.$matches[1]);
                $converters[] = new $fqn($dom);
            }
        }

        $winners = array();
        foreach ($converters as $converter) {
            $score = $converter->getScore();

            if (!isset($winners[$converter->getFormat()])) {
                $winners[$converter->getFormat()] = array(
                    'score' => 0,
                    'obj' => null,
                );
            } elseif ($score > $winners[$converter->getFormat()]['score']) {
                $winners[$converter->getFormat()] = array(
                    'score' => $converter->getScore(),
                    'obj' => $converter,
                );
            }
        }

        $context->add('xml', $dom->saveXml(), 'Original');

        foreach ($winners as $format => $winner) {
            if (!isset($winner['obj'])) {
                throw new \Exception('Could not find converter candidate for XML document');
            }

            $text = $winner['obj']->convert();
            $context->add($format, $text, $winner['obj']->getFragmentDescription());
        }


        return $context;
    }
}
