<?php
mysql_connect("localhost","orientdba","0r1en!") or die(mysql_error() );
mysql_select_db("orientnew");
mysql_query ('SET NAMES utf8');

$query = "SELECT `TITLE`, `TEXT` FROM article WHERE date='2011-12-09' AND section_id='1' AND priority='3'";
$result = mysql_query($query);
$article = mysql_fetch_array($result);

?>
<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="utf-8" />
<title>min db test</title>
</head>

<body>


<h1><?= $article['TITLE']; ?></h1>

<?= $article['TEXT']; ?>

</body>
</html>