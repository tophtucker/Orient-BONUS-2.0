<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title><?=$author->name?> &mdash; The Bowdoin Orient</title>
	<link rel="shortcut icon" href="<?=base_url()?>images/o-32-transparent.png">
	
	<link rel="stylesheet" media="screen" href="<?=base_url()?>css/orient.css?v=3">
	
	<meta name="description" content="<?=htmlspecialchars($author->bio)?>" />
	
	<!-- Facebook Open Graph tags -->
	<meta property="og:title" content="<?=htmlspecialchars($author->name)?>" />
	<meta property="og:description" content="<?=htmlspecialchars($author->bio)?>" />
	<meta property="og:type" content="profile" />
	<meta property="og:image" content="<?=base_url()?>images/o-200.png" /> <!-- #TODO -->
	<meta property="og:url" content="http://bowdoinorient.com/author/<?=$author->id?>" />
	<meta property="og:site_name" content="The Bowdoin Orient" />
	<meta property="fb:admins" content="1233600119" />
	<meta property="fb:app_id" content="342498109177441" />
	
	<!-- for mobile -->
	<link rel="apple-touch-icon" href="<?=base_url()?>images/o-114.png"/>
	<meta name = "viewport" content = "initial-scale = 1.0, user-scalable = no">
	
	<!-- rss -->
	<link rel="alternate" type="application/rss+xml" title="<?=$author->name?> - The Bowdoin Orient" href="<?=base_url()?>rss/author/<?=$author->id?>" />
	
	<script type="text/javascript" src="http://use.typekit.com/rmt0nbm.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	
	<!-- jQuery -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<!-- <script type="text/javascript" src="<?=base_url()?>js/jquery-1.8.0.min.js"></script> -->
	<script type="text/javascript" src="<?=base_url()?>js/jquery-ui-1.8.17.custom.min.js"></script>
	
	<!-- Google Analytics -->
	<script type="text/javascript">
	
	  var _gaq = _gaq || [];
	  _gaq.push(['_setAccount', 'UA-18441903-3']);
	  _gaq.push(['_trackPageview']);
	
	  (function() {
		var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
		ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
	  })();
	
	</script>
	<!-- End Google Analytics -->
	
</head>

<body>

<? $this->load->view('template/bodyheader', $headerdata); ?>

<div id="content">
			
	<section id="articles" class="authorsection">
		
		<h2><?=$author->name?></h2>
		
		<? if(!empty($author->bio)): ?>
			<!-- #TODO: programmatic bio! -->
			<?= $author->bio ?>
		<? endif; ?>
				
		<? if(!empty($author->photo)): ?>
			<figure class="authorpic"><img src="<?=base_url().'images/authors/'.$author->photo?>"></figure>
		<? endif; ?>
				
		<ul class="articleblock">
			<? foreach($articles as $article): ?>
			<li class="<? if(!empty($article->filename_small)): ?> backgrounded<? endif; ?><? if(!$article->published): ?> draft<? endif; ?>"<? if(!empty($article->filename_small)): ?> style="background:url('<?=base_url().'images/'.$article->date.'/'.$article->filename_small?>')"<? endif; ?>>
				<a href="<?=site_url()?>article/<?=$article->id?>">
				<h3><? if($article->series): ?><span class="series"><?=$article->series?>:</span> <? endif; ?>
				<?=$article->title?></h3>
				<? if($article->subhead): ?><h4><?= $article->subhead ?></h4><? endif; ?>
				<p><?=$article->pullquote?></p>
			</a></li>
			<? endforeach; ?>
		</ul>
		
	</section>
	
</div>

<? $this->load->view('template/bodyfooter', $footerdata); ?>

<? $this->load->view('bonus/bonusbar', TRUE); ?>

</body>

</html>