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
	
	<h3><?=date("F j, Y h:i:s a",strtotime("Sunday 12 p.m."))?></h3>
	
	<h4>Tips</h4>
	
	<? if(!empty($tips)): ?>
	<? foreach($tips as $tip): ?>
	<p><?= $tip->tip ?> &mdash;<?=$tip->submitted; ?></p>
	<? endforeach; ?>
	<? endif; ?>

</div>
	
<footer>
	<p class="bonusquote">&ldquo;<?=$quote->quote?>&rdquo;</p>
	<p class="bonusquoteattribution">&mdash; <?=$quote->attribution?></p>
	<p class="sunbug"><a href="<?=base_url()?>">&#x2600;</a></p>
</footer>

</div>

<? $this->load->view('bonus/bonusbar', TRUE); ?>

</body>

</html>