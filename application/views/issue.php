<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>The Bowdoin Orient <?=$issue->volume?>.<?=$issue->issue_number?></title>
	<link rel="shortcut icon" href="<?=base_url()?>images/favicon.png">
	<link rel="stylesheet" media="screen" href="<?=base_url()?>css/orient2012.css?v=1">
	
	<!-- for mobile -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<link rel="apple-touch-icon" href="<?=base_url()?>images/webappicon.png"/>
	<meta name = "viewport" content = "initial-scale = 1.0, user-scalable = no">
	<link rel="stylesheet" href="<?=base_url()?>css/add2home.css">
	<script type="application/javascript" src="<?=base_url()?>js/add2home.js"></script>
	
	<script type="text/javascript" src="http://use.typekit.com/rmt0nbm.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	
	<!-- jQuery -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
    <script type="text/javascript" src="<?=base_url()?>js/iscroll.js"></script>

    <!-- for smooth scrolling [disabled] 
    <script type="text/javascript" src="<?=base_url()?>js/jquery.scrollTo-min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>js/jquery.localscroll-1.2.7-min.js"></script>
	-->
	
	<!-- initiate jQuery and the LocalScroll plugin -->
    <script>
 
    // When the document is loaded...
    /*
    $(document).ready(function()
    {
        // Scroll the whole document
        $('#mainnav').localScroll({
           target:'body'
        });
 		
    });
    */
    
    // iscroll carousel
    var myScroll;	
	function loaded() {
		myScroll = new iScroll('carousel_wrapper', {
			snap: 'li',
			momentum: false,
			hScrollbar: false,
			onScrollEnd: function () {
				document.querySelector('#carousel_indicator > li.active').className = '';
				document.querySelector('#carousel_indicator > li:nth-child(' + (this.currPageX+1) + ')').className = 'active';
			}
		 });
		 
		var interval = setInterval(function () { 
				if (myScroll.currPageX == myScroll.pagesX.length-1) myScroll.scrollToPage(0, 0, 1000); 
				else myScroll.scrollToPage('next', 0, 400); 
			}, 5000); 
		
	}
	document.addEventListener('DOMContentLoaded', loaded, false);
    
    </script>
	
</head>

<body>

<header id="mainhead">
	<div id="head-content">
		<h1 id="wordmark"><a href="<?=site_url()?>"><span class="super">The</span> Bowdoin Orient</a></h1>
		
		<div id="issuedate">
			<? if($previssue): ?><a href="<?=site_url()?>issue/view/<?=$previssue->volume?>/<?=$previssue->issue_number?>">&#x25C4;</a><? endif; ?>
			<a href="#">Vol. <?=$issue->volume?>, No. <?=$issue->issue_number?><br/><span class="issuedatedate"><?=date("M. j, Y",strtotime($issue->issue_date))?></span></a>
			<span id="issue-viewselect"><a href="#"><img src="<?=base_url()?>/images/view-paper.png"></a></span>
			<? if($nextissue): ?><a href="<?=site_url()?>issue/view/<?=$nextissue->volume?>/<?=$nextissue->issue_number?>">&#x25BA;</a>
			<? else: ?><?if(bonus()):?><a href="#" title="Add issue" class="addissue">+</a><? endif;?>
			<? endif;?>
		</div>
		
		<nav id="mainnav">
			<ul>
				<!--
				<li><a href="#News">News</a></li>
				<li><a href="#Opinion">Opinion</a></li>
				<li><a href="#Features">Features</a></li>
				<li><a href="#Arts & Entertainment">A&E</a></li>
				<li><a href="#Sports">Sports</a></li>
				-->
				<li><a href="#">About</a></li>
				<li><a href="#">Subscribe</a></li>
				<li><a href="#">Advertise</a></li>
				<li><a href="http://bowdoinorientexpress.com" style="font-family:helvetica;font-style:italic;">Express</a></li>
				<li><input class="filterinput" type="text" placeholder="Search"></li>
			</ul>
		</nav>
	</div>
</header>

<div id="content">
	
	<div id="carousel_wrapper" style="margin-top: 70px; width: 100%;">
		<div id="carousel_scroller">
			<ul id="carousel" class=""> <!-- class="articleblock" -->
				
				<? if($featurephotos[0]): ?>
				<li class="articletile backgrounded" style="background:url('<?=base_url().'images/'.$issue->issue_date.'/'.$featurephotos[0]->filename_small?>')">
				</li>
				<? endif; ?>
				
				<!-- popular articles -->
				<? foreach($popular as $article): ?>
				<li class="articletile<? if(!empty($article->filename_small)): ?> backgrounded<? endif; ?>"<? if(!empty($article->filename_small)): ?> style="background:url('<?=base_url().'images/'.$issue->issue_date.'/'.$article->filename_small?>')"<? endif; ?>>
					<a href="javascript:window.open('../../../article/view/<?=$article->id?>','_self')">
					<h3><? if($article->type): ?><span class="type"><?=$article->type?>:</span> <? endif; ?>
					<? if($article->series): ?><span class="series"><?=$article->series?>:</span> <? endif; ?>
					<?=$article->title?></h3>
					<? if($article->subhead): ?><h4><?= $article->subhead ?></h4><? endif; ?>
					<p><?=$article->pullquote?></p>
				</a></li>
				<? endforeach; ?>
				
				<!-- tweets -->
				<li>
					<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
					<script>
					new TWTR.Widget({
					  version: 2,
					  type: 'profile',
					  rpp: 6,
					  interval: 30000,
					  width: 'auto',
					  height: 300,
					  theme: {
						shell: {
						  background: '#ffffff',
						  color: '#000000'
						},
						tweets: {
						  background: '#ffffff',
						  color: '#000000',
						  links: '#00008b'
						}
					  },
					  features: {
						scrollbar: true,
						loop: false,
						live: false,
						behavior: 'all'
					  }
					}).render().setUser('bowdoinorient').start();
					</script>
				</li>
				
				<!-- events -->
				<li>
					<script type="text/javascript" src="http://plancast.com/goodies/widgets/sidebar/1/43729"></script>
				</li>
				
			</ul>
		</div>
	</div>
	<div id="carousel_nav" style="clear:both;">
		<div id="carousel_prev" onclick="myScroll.scrollToPage('prev', 0);return false">&larr; prev</div>
		<ul id="carousel_indicator">
			<li class="active">1</li>
			<li>2</li>
			<li>3</li>
			<li>4</li>
			<li>5</li>
			<li>6</li>
			<li>7</li>
		</ul>
		<div id="carousel_next" onclick="myScroll.scrollToPage('next', 0);return false">next &rarr;</div>
	</div>
	
	<? foreach($sections as $section): ?>
		
		<section id="<?=$section->name?>" class="issuesection">
			<h2><?=$section->name?></h2>
			
			<ul class="articleblock">
				<? foreach($articles[$section->name] as $article): ?>
				<li class="articletile<? if(!empty($article->filename_small)): ?> backgrounded<? endif; ?>"<? if(!empty($article->filename_small)): ?> style="background:url('<?=base_url().'images/'.$issue->issue_date.'/'.$article->filename_small?>')"<? endif; ?>>
					<a href="javascript:window.open('../../../article/view/<?=$article->id?>','_self')">
					<h3><? if($article->type): ?><span class="type"><?=$article->type?>:</span> <? endif; ?>
					<? if($article->series): ?><span class="series"><?=$article->series?>:</span> <? endif; ?>
					<?=$article->title?></h3>
					<? if($article->subhead): ?><h4><?= $article->subhead ?></h4><? endif; ?>
					<p><?=$article->pullquote?></p>
				</a></li>
				<? endforeach; ?>
				<? if(bonus()): ?>
				<li class="articletile"><a href="<?=site_url()?>article/add/<?=$issue->volume?>/<?=$issue->issue_number?>/<?=$section->id?>"><h3 class="addarticle">+ Add article</h3></a></li>
				<? endif; ?>
			</ul>
			
		</section>
	
	<? endforeach; ?>
	
	<footer>
		
		<div class="bonusquoteblock">
			<p class="bonusquote">&ldquo;<?=$quote->quote?>&rdquo;</p>
			<p class="bonusquoteattribution">&mdash; <?=$quote->attribution?></p>
		</div>
		
		<p class="vcard">
			<a class="fn org url" href="http://orient.bowdoin.edu" title="The Bowdoin Orient"><span class="organization-name">The Bowdoin Orient</span></a><br>
			<span class="adr">
				<span class="street-address">6200 College Station</span><br>
				<span class="locality">Brunswick</span>, <span class="region">Maine</span> <span class="postal-code">04011</span><br>
				<span class="tel">Telephone: <span class="value">(207) 725-3300</span></span><br>
				<span class="tel">Business phone: <span class="value">(207) 725-3053</span></span>
			</span>
		</p>

		<small>&copy; <?=date("Y")?>, The Bowdoin Orient</a></small>
		
	</footer>

</div>

<? $this->load->view('bonus/bonusbar', TRUE); ?>

</body>

</html>