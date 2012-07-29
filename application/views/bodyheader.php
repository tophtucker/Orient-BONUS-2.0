<!-- Facebook JavaScript SDK -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId=115180665171978";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<!-- end FB JS SDK -->

<script>
$(function() {
	$( "#datepicker" ).datepicker({
		prevText: '&laquo;',
		nextText: '&raquo;',
		dateFormat: "yy-mm-dd",
		onSelect: function(dateText, inst) { 
			window.location.href = '<?=base_url()?>browse/'+dateText; 
		}
	});
});
</script>

<header id="mainhead">
	<div id="head-content">
		<h1 id="wordmark"><a href="<?=site_url()?>"><span class="super">The</span> Bowdoin Orient</a></h1>
		
		<a href="https://twitter.com/bowdoinorient" class="twitter-follow-button" data-show-count="false">Follow @bowdoinorient</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		
		<div class="fb-like" data-href="https://www.facebook.com/bowdoinorient" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
				
		<nav id="mainnav">
			<ul>
				<? if($this->uri->segment(1) == "" || $this->uri->segment(1) == "browse"): ?>
					<li><a href="#News">News</a></li>
					<li><a href="#Opinion">Opinion</a></li>
					<li><a href="#Features">Features</a></li>
					<li><a href="#Arts & Entertainment">A&E</a></li>
					<li><a href="#Sports">Sports</a></li>
				<? endif; ?>
				<? if($this->uri->segment(1) == "article" && !empty($section_id)): ?>
					<li class="<?= ($section_id == "1" ? "active" : "inactive"); ?>"><a href="<?=site_url()."browse/".$date?>#News">News</a></li>
					<li class="<?= ($section_id == "2" ? "active" : "inactive"); ?>"><a href="<?=site_url()."browse/".$date?>#Opinion">Opinion</a></li>
					<li class="<?= ($section_id == "3" ? "active" : "inactive"); ?>"><a href="<?=site_url()."browse/".$date?>#Features">Features</a></li>
					<li class="<?= ($section_id == "4" ? "active" : "inactive"); ?>"><a href="<?=site_url()."browse/".$date?>#Arts & Entertainment">A&E</a></li>
					<li class="<?= ($section_id == "5" ? "active" : "inactive"); ?>"><a href="<?=site_url()."browse/".$date?>#Sports">Sports</a></li>
				<? endif; ?>
				<li>
					<form action="<?=site_url()?>pages/search" id="cse-search-box" method="get">
						<input class="filterinput" type="text" placeholder="Search" name="q">
					</form>
				</li>
				<li><a href="http://bowdoinorientexpress.com" style="font-family:helvetica;font-style:italic;" class="oebug"><img src="<?=base_url().'images/oe-compass-35.png'?>"></a></li>
			</ul>
		</nav>
	</div>
</header>

<div id="subnavbar">
	<?if(isset($date)):?><span id="lastupdated"><?=date("F j, Y",strtotime($date))?></span> <div id="datepicker"></div> <span class="hidemobile">&middot; <?endif;?><?if(isset($volume) && isset($issue_number)):?>&laquo; Vol. <?=$volume?>, No. <?=$issue_number?> &raquo;</span> &middot; <?endif;?><a href="<?=base_url()?>random">Random</a>
	<span id="pages"><?=anchor('pages/about', 'About'); ?> &middot; <?=anchor('pages/subscribe', 'Subscribe'); ?> &middot; <?=anchor('pages/advertise', 'Advertise'); ?> &middot; <span id="submittip">Submit a tip</span></span>
</div>

<div id="submittipform">
	<span class="closebutton">&times;</span>
	<strong>Submit an anonymous tip.</strong><br/>Please leave contact information if willing.<br/>
	<textarea name="tip"></textarea>
	<button id="tipsubmit">Submit</button>
</div>

<script>

$("#lastupdated").click(function () {
	$("#datepicker").toggle();
});

$("#submittip").click(function () {
	$("#submittipform").toggle();
});

$("#submittipform .closebutton").click(function () {
	$("#submittipform").hide();
});

$(document).ready(function() {
     
    //if submit button is clicked
    $('#tipsubmit').click(function () {        
         
        var tip = $('textarea[name=tip]');
         
        //start the ajax
        $.ajax({
            url: "<?=site_url()?>tools/ajax_submittip",
            type: "POST",
            data: 'tip=' + encodeURIComponent(tip.val()),
            cache: false,
            success: function (result) {
                //if ajax_submittip returned 1/true (submit success)
                if (result=='true') {
                    //hide the form
                    $('#submittipform').hide();
                //if process.php returned 0/false (send mail failed)
                } else alert('Error! Sorry. Try emailing us: orient@bowdoin.edu');
            }       
        });
         
        //cancel the submit button default behaviours
        return false;
    }); 
}); 

</script>