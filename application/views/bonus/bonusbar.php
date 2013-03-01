<?if(bonus()):?>

	<div id="bonusbar">
	<span id="bonusbarnav">
		&#x235f; / 	
		<?if(substr(uri_string(),0,6)=="bonus/"):?>
		<a href="<?=site_url()?>">Home</a> 
		<?else:?>
		<a href="<?=site_url()?>bonus/dashboard">Dashboard</a> 
		<?endif;?>
		<a href="<?=site_url()?>bonus/logout/"><button class="bonusbutton">Logout of <?=username()?></button></a>
	</span>

	<?if(substr(uri_string(),0,8)=="article/"):?>
		<button id="publisharticle" class="bonusbutton" style="<? if($article->published): ?>display:none;<?endif;?>">Publish</button>
		<button id="savearticle" class="bonusbutton">Save</button>
		<span id="bonustools">
			<span id="savenotify"></span>
			Vol. <input type="text" name="volume" id="volume" size="2" value="<?=$article->volume?>" />
			/ No. <input type="text" name="issue_number" id="issue_number" size="2" value="<?=$article->issue_number?>" />
			<input type="hidden" name="section_id" id="section_id" size="2" value="<?=$article->section_id?>" />
			/ Priority <input type="text" name="priority" id="priority" size="2" value="<?=$article->priority?>" />
			<? if($article->published): ?>/ <a href="#" class="delete" id="unpublish">Unpublish</a><?endif;?>
			/ <a href="#" class="delete" id="deletearticle">Delete</a>
		</span>
	<?endif;?>
	
	</div>

<?else:?>

	<div id="bonushook">
		<a href="<?=site_url()?>bonus/login">&#x235f;</a>
	</div>

<?endif;?>