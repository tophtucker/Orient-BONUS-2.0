<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title><?=$article->title?> - The Bowdoin Orient</title>
	<link rel="shortcut icon" href="<?=base_url()?>images/favicon-o.png">
	
	<link rel="stylesheet" media="screen" href="<?=base_url()?>css/orient2012.css?v=1">
	
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>/js/jquery.scrollTo-min.js"></script>
	
	<!-- for mobile -->
	<!--<meta name="apple-mobile-web-app-capable" content="yes">-->
	<link rel="apple-touch-icon" href="<?=base_url()?>images/webappicon.png"/>
	<meta name = "viewport" content = "initial-scale = 1.0, user-scalable = no">
		
	<script type="text/javascript" src="http://use.typekit.com/rmt0nbm.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	
	<!-- SwipeView (for slideshows) -->
	<link rel="stylesheet" media="screen" href="<?=base_url()?>css/swipeview.css?v=1">
	<script type="text/javascript" src="<?= base_url() ?>js/swipeview.js"></script>
	
	<!-- MediaBugs script (for reporting errors to third party auditor) -->
	<script type="text/javascript" src="http://mediabugs.org/widget/widget.js"></script>
	
	<? if(bonus()): ?>
	<script type="text/javascript" src="<?=base_url()?>js/jquery-ui-1.8.17.custom.min.js"></script>
	<script>
	
	var titleedited=false;
	var subtitleedited=false;
	var bodyedited=false;
	var photoadded=false;
	var hasphoto=false;
	var photocreditedited=false;
	var photocaptionedited=false;
	
	$(document).ready(function()
    {
    
    	$('#articletitle').keydown(function() {
    		titleedited=true;
    		$('#articletitle').css("color", "darkred");
		});
		$('#articlesubtitle').keydown(function() {
    		subtitleedited=true;
    		$('#articlesubtitle').css("color", "darkred");
		});
		$('#articlebody').keydown(function() {
    		bodyedited=true;
    		$('#articlebody').css("color", "darkred");
		});
		$('#photocredit').keydown(function() {
    		photocreditedited=true;
    		$('#photocredit').css("color", "darkred");
		});
		$('#photocaption').keydown(function() {
    		photocationedited=true;
    		$('#photocaption').css("color", "darkred");
		});
		
    	$("#savearticle").click(function() {
			
			// if an image was added, save it.
			// $('#dnd-holder').length != 0 && $('#dnd-holder').attr('class') == 'backgrounded'
			if(photoadded) {
				$.ajax({
					type: "POST",
					url: "<?=site_url()?>article/ajax_add_photo/<?=$article->date?>/<?=$article->id?>",
					data: 
						"img=" + $('#dnd-holder').css('background-image') + 
						"&credit=" + $("#photocredit").html() +
						"&caption=" + $("#photocaption").html(),
					success: function(result){
						$('#dnd-holder').css('border', '0');
						// set hasphoto to true; set photoadded to false? ugh.
					}
				});
			}
			
			var ajaxrequest = 
					"title=" + $("#articletitle").html() + 
					"&subtitle=" + $("#articlesubtitle").html() +
					"&author=" + $("#addauthor").html() +
					"&authorjob=" + $("#addauthorjob").html();
			if(bodyedited) { ajaxrequest += "&body=" + $("#articlebody").html(); }
			// write photocredit and caption if there's a photo that hasn't just been added and its credit/caption were edited
			
			// write title, subtitle, author, authorjob, body
			// (regardless of whether they've been edited. sloppy.)
			$.ajax({
				type: "POST",
				url: "<?=site_url()?>article/edit/<?=$article->id?>",
				data: ajaxrequest,
				success: function(result){
					if(result=="refresh") { window.location.reload(); }
					$("#savenotify").html(result);
					$("#savenotify").show();
					$("#savenotify").fadeOut(2000);
				}
			});
			
			// if an image already exists, update its credit & caption.
			// TO-DO
									
		} );
    });
    
    
	(function ($) {
		var original = $.fn.val;
		$.fn.val = function() {
			if ($(this).is('*[contenteditable=true]')) {
				return $.fn.html.apply(this, arguments);
			};
			return original.apply(this, arguments);
		};
	})(jQuery);
	
    $(function() {
		$( "#addauthor" ).autocomplete({
			source: "<?=site_url()?>article/ajax_suggest/author/name"
		});
	});
	
	$(function() {
		$( "#addauthorjob" ).autocomplete({
			source: "<?=site_url()?>article/ajax_suggest/job/name"
		});
	});
	
	$(function() {
		$( "#photocredit" ).autocomplete({
			source: "<?=site_url()?>article/ajax_suggest/author/name"
		});
	});
	
	</script>
	<? endif; ?>

</head>

<body>

<? $this->load->view('bodyheader'); ?>

<div id="content">
	
	<article id="mainstory">
		
		<header>
			<hgroup>
				<h2 id="articletitle" class="articletitle"<?if(bonus()):?> contenteditable="true"<?endif;?>><?=$article->title?></h2>
				<h3 id="articlesubtitle" class="articlesubtitle"<?if(bonus()):?> contenteditable="true"<?endif;?>><? if(isset($article->subhead)): ?><?=$article->subhead?><? endif; ?></h3>
			</hgroup>
			<? if($authors): foreach($authors as $key => $author): ?>
				<div class="authorbox">
					<p class="articleauthor"><?=$author->authorname?></p>
					<p class="articleauthorjob"><?=$author->jobname?></p>
				</div>
			<? endforeach; endif; ?>
			<? if(bonus()): ?>
				<div class="authorbox">
					<p class="articleauthor" id="addauthor" contenteditable="true" style="color:darkred">+</p>
					<p class="articleauthorjob" id="addauthorjob" contenteditable="true" style="color:red">+</p>
				</div>
			<? endif; ?>
			
			<p class="articledate"><time pubdate datetime="<?=$article->date?>"><?=date("F j, Y",strtotime($article->date))?></time></p>
		</header>                
		
		<? if($photos): ?>
		<figure id="articlemedia">
			<? if(count($photos) == 1): ?>
				<img src="<?=base_url()?>images/<?=$article->date?>/<?=$photos[0]->filename_large?>">
			<? else: ?>
				<div id="swipeview_wrapper"></div>
				<div id="swipeview_relative_nav">
					<span id="prev" onclick="carousel.prev();hasInteracted=true">&laquo;</span>
					<span id="next" onclick="carousel.next();hasInteracted=true">&raquo;</span>
				</div>
				<ul id="swipeview_nav">
					<? foreach($photos as $key => $photo): ?>
					<li <? if($key==0): ?>class="selected"<? endif; ?> onclick="carousel.goToPage(<?=$key?>);hasInteracted=true"></li>
					<? endforeach; ?>
				</ul>
			<? endif; ?>
			<figcaption>
				<p class="photocredit"<? if(count($photos) > 1): ?> style="margin-top: 0;text-shadow:none;color:gray;"<? endif;?>><? if(!empty($photos[0]->photographer_id)): ?><?= $photos[0]->photographer_name ?><? else: ?><?= $photos[0]->credit ?><? endif; ?></p>
				<p class="photocaption"><?=$photos[0]->caption?></p>
			</figcaption>
			</li>
		</figure>
		<? else: ?>
			<? if(bonus()): ?>
				<style>
					#dnd-holder { border: 2px dashed #ccc; box-sizing: border-box; width: 500px; height: 300px; background-size: cover;}
					#dnd-holder.hover { border: 10px dashed #333; }
				</style>
				<figure id="articlemedia">
					<div id="dnd-holder"></div>
					<figcaption class="bonus">
						<p id="photocredit" class="photocredit" style="margin-top: 0;text-shadow:none;color:gray;" contenteditable="true">Credit</p>
						<p id="photocaption" class="photocaption" contenteditable="true"><b>Caption:</b> caption.</p>
					</figcaption>
				</figure>
			<? endif; ?>
		<? endif; ?>
		
		<div id="articlebody" class="articlebody"<?if(bonus()):?> contenteditable="true"<?endif;?>><?=$body->body?></div>
		
		<div id="articlefooter">
			<ul>
				<li>
					<? //just concatenating authors, messily
					$authorsString = '';
					if($authors) { 
						foreach($authors as $key => $author) {
							if($key != 0) $authorsString .= ', ';
							$authorsString .= $author->authorname;
						} 
					}
					?>
					<a href="#" onclick="reportMediaBug(
						'<?= $article->title ?>',
						'The Bowdoin Orient',
						'<?= $authorsString ?>',
						'<?= $article->date ?>',
						'<?= current_url(); ?>');">
					Report an error</a>
				</li>
			</ul>
		</div>
	  
	</article>

	<? $this->load->view('bodyfooter', $footerdata); ?>

</div>

<? $this->load->view('bonus/bonusbar', TRUE); ?>

<? if(bonus()): ?>

<script>
	// drag-and-drop image
	var holder = document.getElementById('dnd-holder');
	if(holder) {
		holder.ondragover = function () { this.className = 'hover'; return false; };
		holder.ondragend = function () { this.className = ''; return false; };
		holder.ondrop = function (e) {
			this.className = '';
			e.preventDefault();
			
			var file = e.dataTransfer.files[0],
				reader = new FileReader();
			reader.onload = function (event) {
				photoadded=true;
				holder.style.background = 'url(' + event.target.result + ')';
				holder.style.borderColor = 'darkred';
				holder.className += "backgrounded";
				$('figcaption.bonus').show();
			};
			reader.readAsDataURL(file);
			
			return false;
		};
	};
</script>

<? endif; ?>

<? if(count($photos) > 1): ?>
	<!-- SwipeView. Only needed for slideshows. -->
	<script type="text/javascript">
	var	carousel,
		el,
		i,
		page,
		hasInteracted = false,
		dots = document.querySelectorAll('#swipeview_nav li'),
		slides = [
			<? foreach($photos as $key => $photo): ?>
				<? if($key > 0): ?>,<? endif; ?>
				'<img src="<?= base_url() ?>images/<?= $article->date ?>/<?= $photo->filename_large ?>">'
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
<? endif; ?>

</body>

</html>