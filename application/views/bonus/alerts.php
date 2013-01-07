<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>BONUS - Alerts</title>
	<link rel="shortcut icon" href="<?=base_url()?>/images/favicon.png">
	<link rel="stylesheet" media="screen" href="<?=base_url()?>/css/bonus.css?v=1">
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<script type="text/javascript" src="http://use.typekit.com/rmt0nbm.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
</head>

<body>

<div id="container">

<header>
	<h1>>BONUS</h1>
	<h2>Bowdoin Orient Network Update System 2.0</h2>
</header>

<div id="content">
		
	<h3>Alerts</h3>
	
	<nav>
	<ul>
	<li><?=anchor('bonus/dashboard','Dashboard')?></li>
	<li><?=anchor('bonus/authors','Authors')?></li>
	<li><?=anchor('bonus/alerts','Alerts')?></li>
	</ul>
	</nav>	

	<h4>Add Alert</h4>
	
	<?= form_open() ?>
	Message (HTML): 
	<br/><textarea width="30" height="2" name="message"></textarea>
	<br/>Start date: <input type="datetime" name="start_date" value="<?=date("Y-m-d H:i:s",time())?>">
	<br/>End date: <input type="datetime" name="end_date">
	<br/>Urgent: <input type="checkbox" name="urgent" value="1"/>
	<?= form_submit('submit',"Add alert") ?>
	<?= form_close() ?>
	
	
	<h4>Existing alerts</h4>
	
	<? if(!empty($alerts)): ?>
	<table>
	<th><tr><td>Message</td><td>Urgent?</td><td>Start date</td><td>End date</td><td>Delete?</td></tr></th>
	<? foreach($alerts as $alert): ?>
	<tr>
	<td><?=$alert->message; ?></td>
	<td><?=$alert->urgent; ?></td>
	<td><?=$alert->start_date; ?></td>
	<td><?=$alert->end_date; ?></td>
	<td><?=anchor('bonus/deletealert/'.$alert->id,'&times;')?></td>
	<tr>
	<? endforeach; ?>
	</table>
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