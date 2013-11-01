<?php
/**
 * Sfdc CLI client
 * @author Daniel Leech <daniel@dantleech.com>
 */
require(__DIR__.'/vendor/autoload.php');

if (!isset($argv[1])) {
    die(<<<HERE
Sfdc - Symfony Fragment Documentation Converter
-----------------------------------------------
by Daniel Leech (daniel@dantleech.com)

CLI tool for converting an XML documentation code fragment into
the other code fragments (e.g. PHP, YAML, Annotations)

Usage:
  
    $ php sfdc.php codefragfilename.xml

Alternatively use the web interface (index.php)

HERE
);
    exit(1);
}

$file = $argv[1];

if (!file_exists($file)) {
    echo "File ".$file." not found";
    exit(1);
}

$contents = file_get_contents($file);

$sfdc = new Sfdc\Sfdc($contents);
$context = $sfdc->run();

foreach ($context->getFragments() as $format => $fragment) {
?>
<?php echo $format."\n".str_repeat('-', strlen($format))."\n\n" ?>
<?php echo $fragment['content'] ?>
<?php echo "\n"; ?>
<?php
}

exit(0);
