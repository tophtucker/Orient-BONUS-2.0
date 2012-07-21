<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>BONUS</title>
	<link rel="shortcut icon" href="<?=base_url()?>/images/favicon.png">
	<link rel="stylesheet" media="screen" href="<?=base_url()?>/css/bonus.css?v=1">
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<script type="text/javascript" src="http://use.typekit.com/rmt0nbm.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</head>

<body>

<div id="container">

<header>
	<h1>B.O.N.U.S.</h1>
	<h2>Bowdoin Orient Network Update System 2.0</h2>
</header>

<div id="content">
	
	<?= validation_errors('<div class="error">', '</div>'); ?>
	<?= form_open('bonus/verifylogin/'); ?>
		<input type="text" size="20" id="username" name="username" placeholder="Username" autofocus/><br/>
		<input type="password" size="20" id="passowrd" name="password" placeholder="Password"/><br/>
		<input type="hidden" name="referrer" value="<?=$referrer?>" />
		<button type="submit">Go</button>
	</form>

</div>
	
<footer>
	<p class="bonusquote">&ldquo;<?=$quote->quote?>&rdquo;</p>
	<p class="bonusquoteattribution">&mdash; <?=$quote->attribution?></p>
	<p class="sunbug"><a href="<?=base_url()?>">&#x2600;</a></p>
</footer>

</div>

</body>

</html>