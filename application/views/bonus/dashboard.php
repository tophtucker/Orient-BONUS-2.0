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
		
	<h3>Dashboard</h3>
	
	<p>Nothing to see here at the moment. If you have any ideas, tell Toph.</p>

</div>
	
<footer>
	<p class="bonusquote">&ldquo;<?=$quote->quote?>&rdquo;</p>
	<p class="bonusquoteattribution">&mdash; <?=$quote->attribution?></p>
	<p>&#x2601; <span style="font-size:125%">&#x2600;</span> &#x2601;
</footer>

</div>

<? $this->load->view('bonus/bonusbar', TRUE); ?>

</body>

</html>