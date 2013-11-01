# Symfony Documentation XML Fragment Converter

This application converts well defined XML fragments into alternative formats,

e.g.

````bash
$ php sfdc.php test/Type/SfContainer3.xml 
xml
---

<?xml version="1.0" encoding="UTF-8"?>
<container xmlns="http://symfony.com/schema/dic/services" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://symfony.com/schema/dic/services http://symfony.com/schema/dic/services/services-1.0.xsd">

    <services>
        <service id="my_mailer" class="Acme\HelloBundle\Mailer">
            <argument>sendmail</argument>
        </service>
    </services>
</container>

yaml
----

services:
    my_mailer:
        class: Acme\HelloBundle\Mailer
        arguments:
            - sendmail

php
---

use Symfony\Component\DependencyInjection\Reference;
// ...

$container->register('my_mailer', 'Acme\HelloBundle\Mailer')
  ->addArgument('sendmail')
;
````

There is also a web interface accessed via `index.php`.

## Converters

The application works by asking each class in a set of "Converter" classes if
they can convert the given XML fragment. Each converter returns a score
depending on how specific it is.
