<?if(bonus()):?>

	<div id="bonusbar">
	<p>&#x235f; BONUS Bar / 
	<a href="<?=site_url()?>issue/">Home</a> / 
	<a href="<?=site_url()?>bonus/dashboard">Dashboard</a> / 
	<a href="<?=site_url()?>bonus/logout/">Logout of <?=username()?></a>
	<?if(substr(uri_string(),0,8)=="article/"):?>
	<button id="savearticle">Save article</button>
	<span id="savenotify">Saved</span>
	<?endif;?>
	</p>
	</div>

<?else:?>

	<div id="bonushook">
	<p><a href="<?=site_url()?>bonus/login">&#x235f;</a></p>
	</div>

<?endif;?>