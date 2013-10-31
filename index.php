<?php
require(__DIR__.'/lib/lib.php');
?>

<html>
<head>
<title>Symfony Code Fragment COnverter</title>
</head>
<body>
<h1>Doc Converter</h1>
<form action="#">
<label for="fragment">XML to convert</label>
<br/>
<textarea name="fragment">
</textarea>
<br/>
<button type="submit">Convert XML</button>
</form>
</body>
</html>

<?php

if ($_POST) {
    $docConv = new Sfdc($_POST['fragment']);
}
