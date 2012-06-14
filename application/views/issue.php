<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title>The Bowdoin Orient</title>
	<link rel="shortcut icon" href="<?=base_url()?>images/favicon-o.png">
	<link rel="stylesheet" media="screen" href="<?=base_url()?>css/orient2012.css?v=1">
	
	<!-- for mobile -->
	<link rel="apple-touch-icon" href="<?=base_url()?>images/webappicon.png"/>
	<meta name = "viewport" content = "initial-scale = 1.0, user-scalable = no">
	
	<script type="text/javascript" src="http://use.typekit.com/rmt0nbm.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	
	<!-- jQuery -->
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	
	<!-- SwipeView (for carousel) -->
	<link rel="stylesheet" media="screen" href="<?=base_url()?>css/swipeview.css?v=1">
	<script type="text/javascript" src="<?= base_url() ?>js/swipeview.js"></script>

    <!-- for smooth scrolling -->
    <script type="text/javascript" src="<?=base_url()?>js/jquery.scrollTo-min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>js/jquery.localscroll-1.2.7-min.js"></script>
	
	<!-- initiate jQuery and the LocalScroll plugin -->
    <script>
 
    // When the document is loaded...
    $(document).ready(function()
    {
        // Set up localScroll smooth scroller to scroll the whole document
        $('#mainnav').localScroll({
           target:'body'
        });
 		
    });
    
    </script>
	
</head>

<body>

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

<header id="mainhead">
	<div id="head-content">
		<h1 id="wordmark"><a href="<?=site_url()?>"><span class="super">The</span> Bowdoin Orient</a></h1>
		
		<a href="https://twitter.com/bowdoinorient" class="twitter-follow-button" data-show-count="false">Follow @bowdoinorient</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
		
		<div class="fb-like" data-href="https://www.facebook.com/bowdoinorient" data-send="false" data-layout="button_count" data-width="100" data-show-faces="false"></div>
				
		<nav id="mainnav">
			<ul>
				<li><a href="#News">News</a></li>
				<li><a href="#Opinion">Opinion</a></li>
				<li><a href="#Features">Features</a></li>
				<li><a href="#Arts & Entertainment">A&E</a></li>
				<li><a href="#Sports">Sports</a></li>
				<li><input class="filterinput" type="text" placeholder="Search"></li>
				<li><a href="http://bowdoinorientexpress.com" style="font-family:helvetica;font-style:italic;" class="oebug"><img src="<?=base_url().'images/oe-compass-35.png'?>"></a></li>
			</ul>
		</nav>
	</div>
</header>

<div id="subnavbar">
<font color="black">Last updated June 12, 2012</font> - Vol. 141, No. 12 - Archives
<span style="float:right">About - Subscribe - Advertise - <font color="darkred">Submit a tip</font></span>
</div>

<div id="content">
	
	<section id="abovethefold" class="">
		
		<!-- carousel -->
		<div id="carousel">
			<div id="swipeview_wrapper"></div>
			<div id="swipeview_relative_nav">
				<span id="prev" onclick="carousel.prev();hasInteracted=true">&laquo;</span>
				<span id="next" onclick="carousel.next();hasInteracted=true">&raquo;</span>
			</div>
			<ul id="swipeview_nav">
				<? foreach($popular as $key => $article): ?>
				<li <? if($key==0): ?>class="selected"<? endif; ?> onclick="carousel.goToPage(<?=$key?>);hasInteracted=true"></li>
				<? endforeach; ?>
			</ul>			
		</div>
		
		<!-- tweets -->
		<div id="twitter-widget">
			<h2>Twitter</h2>
			<script charset="utf-8" src="http://widgets.twimg.com/j/2/widget.js"></script>
			<script>
			new TWTR.Widget({
			  version: 2,
			  type: 'profile',
			  rpp: 10,
			  interval: 5000,
			  width: 'auto',
			  height: 400,
			  theme: {
				shell: {
				  background: 'transparent',
				  color: '#000000'
				},
				tweets: {
				  background: 'transparent',
				  color: '#555',
				  links: '#00008B'
				}
			  },
			  features: {
				scrollbar: true,
				loop: false,
				live: true,
				behavior: 'default'
			  }
			}).render().setUser('bowdoinorient').start();
			</script>
		</div>
		
		<!-- popular articles -->
		<div id="popular">
			<h2>Popular</h2>
			<ul class="articleblock">
				<? foreach($popular as $article): ?>
				<li class="smalltile">
					<a href="<?=site_url()?>article/view/<?=$article->id?>">
					<h3><? if($article->type): ?><span class="type"><?=$article->type?>:</span> <? endif; ?>
					<? if($article->series): ?><span class="series"><?=$article->series?>:</span> <? endif; ?>
					<?=$article->title?></h3>
					<? if($article->subhead): ?><h4><?= $article->subhead ?></h4><? endif; ?>
					<p><?=$article->pullquote?></p>
				</a></li>
				<? endforeach; ?>
			</ul>
		</div>
		
	</section>
	
	<? foreach($sections as $section): ?>
		
		<section id="<?=$section->name?>" class="issuesection">
			<h2><?=$section->name?></h2>
			
			<ul class="articleblock">
				<? foreach($articles[$section->name] as $article): ?>
				<li class="<? if(!empty($article->filename_small)): ?> backgrounded<? endif; ?>"<? if(!empty($article->filename_small)): ?> style="background:url('<?=base_url().'images/'.$issue->issue_date.'/'.$article->filename_small?>')"<? endif; ?>>
					<a href="<?=site_url()?>article/view/<?=$article->id?>">
					<h3><? if($article->type): ?><span class="type"><?=$article->type?>:</span> <? endif; ?>
					<? if($article->series): ?><span class="series"><?=$article->series?>:</span> <? endif; ?>
					<?=$article->title?></h3>
					<? if($article->subhead): ?><h4><?= $article->subhead ?></h4><? endif; ?>
					<p><?=$article->pullquote?></p>
				</a></li>
				<? endforeach; ?>
				<? if(bonus()): ?>
				<li class=""><a href="<?=site_url()?>article/add/<?=$issue->volume?>/<?=$issue->issue_number?>/<?=$section->id?>"><h3 class="addarticle">+ Add article</h3></a></li>
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

<!-- SwipeView. Only needed for slideshows. -->
<script type="text/javascript">
var	carousel,
	el,
	i,
	page,
	hasInteracted = false,
	dots = document.querySelectorAll('#swipeview_nav li'),
	slides = [
		<? foreach($popular as $key => $article): ?>
			<? if($key > 0): ?>,<? endif; ?>
			'<div class="carouseltile"><h3><?= $article->title ?></h3></div>'
		<? endforeach; ?>
	];

carousel = new SwipeView('#swipeview_wrapper', {
	numberOfPages: slides.length,
	hastyPageFlip: true
});

// Load initial data
for (i=0; i<3; i++) {
	page = i==0 ? slides.length-1 : i-1;

	el = document.createElement('span');
	el.innerHTML = slides[page];
	carousel.masterPages[i].appendChild(el)
}

carousel.onFlip(function () {
	var el,
		upcoming,
		i;

	for (i=0; i<3; i++) {
		upcoming = carousel.masterPages[i].dataset.upcomingPageIndex;

		if (upcoming != carousel.masterPages[i].dataset.pageIndex) {
			el = carousel.masterPages[i].querySelector('span');
			el.innerHTML = slides[upcoming];
		}
	}
	
	document.querySelector('#swipeview_nav .selected').className = '';
	dots[carousel.pageIndex].className = 'selected';
});


// timer for carousel autoplay
function loaded() {
	var interval = setInterval(function () { 
			if(!hasInteracted) carousel.next(); 
		}, 5000); 
	
}
document.addEventListener('DOMContentLoaded', loaded, false);

</script>

</body>

</html>