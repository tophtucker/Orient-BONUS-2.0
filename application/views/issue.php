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

<? $this->load->view('bodyheader'); ?>

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
		<div id="twitter-widget" class="hidetablet">
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
	
	<? $this->load->view('bodyfooter', $footerdata); ?>
	
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
			'<div class="carouseltile">'+
				'<a href="<?=site_url()?>article/view/<?=$article->id?>"><h3><?= $article->title ?></h3></a>'+
				<? if(!empty($article->filename_small)): ?>'<img src="<?=base_url().'images/'.$issue->issue_date.'/'.$article->filename_small?>">'+<? endif; ?>
				'<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Cras gravida rhoncus porta. Suspendisse libero turpis, viverra ut molestie in, varius a erat. Sed condimentum scelerisque elit a fermentum. Nunc malesuada rhoncus urna, quis lobortis ante viverra ut. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Curabitur interdum lacinia tempor. Curabitur commodo augue eget urna facilisis scelerisque. Vivamus adipiscing rutrum tristique.</p>'+
				'<p>Nulla convallis tempor dapibus. Pellentesque ornare enim quis nibh convallis rutrum. Sed eu sapien at felis ultrices semper. Nulla at auctor purus. In sodales tempor nisl sed congue. Suspendisse ut interdum eros. Nulla a massa eget augue sagittis placerat nec in enim. Aenean sed felis et nibh pharetra luctus. Praesent fermentum imperdiet pharetra. Nunc at convallis diam. Phasellus a sem turpis. Aliquam quis mi ut nulla facilisis ultrices.</p>'+
			'</div>'
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