<?php
require(__DIR__.'/vendor/autoload.php');
?>

<html>
<head>
<title>Symfony Code Fragment COnverter</title>
</head>
<body>
<h1>Doc Converter</h1>
<form action="#" method="post">
<label for="fragment">XML to convert</label>
<br/>
<textarea name="fragment" rows="20" cols="80">
<?php echo @$_POST['fragment'] ?>
</textarea>
<br/>
<input type="submit" value="Convert XML"/>
</form>
</body>
</html>

<?php
if ($_POST) {
    ini_set('display_errors', 1);
    $sfdc = new Sfdc\Sfdc($_POST['fragment']);
    $context = $sfdc->run();

    foreach ($context->getFragments() as $format => $fragment) {
        ?>
<h2><?php echo $format ?></h2>
<p><?php echo $fragment['description'] ?></p>
<pre>
<?php echo htmlentities($fragment['content']) ?>
</pre>
        <?php
    }
}
