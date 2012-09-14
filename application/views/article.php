<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8" />
	<title><?=$article->title?> &mdash; The Bowdoin Orient</title>
	<link rel="shortcut icon" href="<?=base_url()?>images/o-32-transparent.png">
	
	<link rel="stylesheet" media="screen" href="<?=base_url()?>css/orient2012.css?v=1">
	
	<meta name="description" content="<?=htmlspecialchars($article->pullquote)?>" />
	
	<!-- Facebook Open Graph tags -->
	<meta property="og:title" content="<?=htmlspecialchars($article->title)?>" />
	<meta property="og:description" content="<?=htmlspecialchars($article->pullquote)?>" />
	<meta property="og:type" content="article" />
	<? if($photos): ?>
		<meta property="og:image" content="<?=base_url()?>images/<?=$article->date?>/<?=$photos[0]->filename_large?>" />
	<? else: ?>
		<meta property="og:image" content="<?=base_url()?>images/o-200.png" />
	<? endif; ?>
	<meta property="og:url" content="http://bowdoinorient.com/article/<?=$article->id?>" />
	<meta property="og:site_name" content="The Bowdoin Orient" />
	<meta property="fb:admins" content="1233600119" />
	<meta property="fb:app_id" content="342498109177441" />
	<!-- <meta property="fb:page_id" content="113269185373845" /> -->
	
	<!-- jQuery -->
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.6.4/jquery.min.js"></script>
	<!-- <script type="text/javascript" src="<?=base_url()?>js/jquery-1.8.0.min.js"></script> -->
	<script type="text/javascript" src="<?=base_url()?>js/jquery-ui-1.8.17.custom.min.js"></script>
	<script type="text/javascript" src="<?=base_url()?>js/jquery.scrollTo-min.js"></script>
	
	<!-- for mobile -->
	<link rel="apple-touch-icon" href="<?=base_url()?>images/o-114.png"/>
	<meta name = "viewport" content = "initial-scale = 1.0, user-scalable = no">
		
	<script type="text/javascript" src="http://use.typekit.com/rmt0nbm.js"></script>
	<script type="text/javascript">try{Typekit.load();}catch(e){}</script>
	
	<!-- SwipeView (for slideshows) -->
	<link rel="stylesheet" media="screen" href="<?=base_url()?>css/swipeview.css?v=1">
	
	<!-- MediaBugs script (for reporting errors to third party auditor) -->
	<script type="text/javascript" src="http://mediabugs.org/widget/widget.js"></script>
	
	<!-- Pinterest script -->
	<script type="text/javascript" src="//assets.pinterest.com/js/pinit.js"></script>
	
	<? if(bonus()): ?>
	<script>
	
	var titleedited=false;
	var subtitleedited=false;
	var bodyedited=false;
	var photoadded=false;
	var hasphoto=false;
	var photocreditedited=false;
	var photocaptionedited=false;
	
	// thanks Mark Seecof!
	// http://www.php.net/manual/en/function.urlencode.php#85903
	function urlencode(s) {
		s = encodeURIComponent(s);
		return s.replace(/~/g,'%7E').replace(/%20/g,'+');
	}
	
	$(document).ready(function()
    {
    
    	$('#articletitle').keydown(function() {
    		titleedited=true;
    		$('#articletitle').css("color", "darkred");
		});
		$("#articletitle").bind('paste', function() {
			titleedited=true;
    		$('#articletitle').css("color", "darkred");
		});	
		
		$('#articlesubtitle').keydown(function() {
    		subtitleedited=true;
    		$('#articlesubtitle').css("color", "darkred");
		});
		$('#articlesubtitle').bind('paste', function() {
    		subtitleedited=true;
    		$('#articlesubtitle').css("color", "darkred");
		});
		
		$('#articlebody').keydown(function() {
    		bodyedited=true;
    		$('#articlebody').css("color", "darkred");
		});
		$('#articlebody').bind('paste', function() {
    		bodyedited=true;
    		$('#articlebody').css("color", "darkred");
		});
		
		$('#photocredit').keydown(function() {
    		photocreditedited=true;
    		$('#photocredit').css("color", "darkred");
		});
		$('#photocredit').bind('paste', function() {
    		photocreditedited=true;
    		$('#photocredit').css("color", "darkred");
		});
		
		$('#photocaption').keydown(function() {
    		photocationedited=true;
    		$('#photocaption').css("color", "darkred");
		});
		$('#photocaption').bind('paste', function() {
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
					"title=" + urlencode($("#articletitle").html()) + 
					"&subtitle=" + urlencode($("#articlesubtitle").html()) +
					"&series=" + urlencode($("#series").html()) +
					"&author=" + urlencode($("#addauthor").html()) +
					"&authorjob=" + urlencode($("#addauthorjob").html()) +
					"&volume=" + urlencode($('input[name=volume]').val()) +
					"&issue_number=" + urlencode($('input[name=issue_number]').val()) +
					"&section_id=" + urlencode($('input[name=section_id]').val()) +
					"&priority=" + urlencode($('input[name=priority]').val()) +
					"&published=" + $('input[name=published]').prop('checked') +
					"&featured=" + $('input[name=featured]').prop('checked') +
					"&pullquote=" + urlencode($('textarea[name=pullquote]').val());
			if(bodyedited) { ajaxrequest += "&body=" + urlencode($("#articlebody").html()); }
			
			// #TODO: if an image already exists, update its credit & caption (if edited).
			
			// write title, subtitle, author, authorjob, bonus-meta stuff
			// (regardless of whether they've been edited. sloppy.)
			// and body, only if it's been edited
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
			
									
		} );
		
		$("#removephotos").click(function(event) {
			event.preventDefault()
			//note: "data:" is totally unused, but what'd happen if it weren't there??? (well, test!)
			$.ajax({
				type: "POST",
				url: "<?=site_url()?>article/ajax_remove_photos/<?=$article->id?>",
				data: "remove=true",
				success: function(result){
					if(result=="success") {
						//hide photos (gotta refresh to add again, as it works now)
						$('#dnd-holder').hide();
						$('#articlemedia').hide();
					}
					//show alert
					$("#savenotify").html(result);
					$("#savenotify").show();
					$("#savenotify").fadeOut(2000);
				}
			});
		} );
		
		$("#deletearticle").click(function(event) {
			event.preventDefault()
			
			if(confirm("Are you sure you want to delete this article?")) {
				//note: "data:" is totally unused, but what'd happen if it weren't there??? (well, test!)
				$.ajax({
					type: "POST",
					url: "<?=site_url()?>article/ajax_delete_article/<?=$article->id?>",
					data: "remove=true",
					success: function(result){
						if(result=="1") {
							//return home
							window.location = "<?=site_url()?>";
						}
						//show alert
						$("#savenotify").html(result);
						$("#savenotify").show();
						$("#savenotify").fadeOut(2000);
					}
				});
			}
			
		} );
		
    });
    
    // ugh, i forget what this is even for.
    // i think to help autocomplete work on contenteditable?
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
	
	$(function() {
		$( "#series" ).autocomplete({
			source: "<?=site_url()?>article/ajax_suggest/series/name"
		});
	});
	
	</script>
	<? endif; ?>

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

<? $this->load->view('bodyheader', $headerdata); ?>

<div id="content">
	
	<article id="mainstory">
		
		<header>
			<hgroup>
				
				<? if($article->type): ?><h3 id="type" class="type"><?=$type->name?></h3> <? endif; ?>
				<? if($article->series || bonus()): ?>
					<h3 id="series" class="series"<?if(bonus()):?> contenteditable="true"<?endif;?>>
						<? if(!bonus()): ?><a href="<?=site_url()?>series/<?=$series->id?>"><? endif; ?>
						<?=$series->name?>
						<? if(!bonus()): ?></a><? endif; ?>
					</h3>
				<? endif; ?>
				
				<h2 id="articletitle" class="articletitle"<?if(bonus()):?> contenteditable="true"<?endif;?>><?=$article->title?></h2>
				<h3 id="articlesubtitle" class="articlesubtitle"<?if(bonus()):?> contenteditable="true"<?endif;?>><? if(isset($article->subhead)): ?><?=$article->subhead?><? endif; ?></h3>
				
			</hgroup>
			<? if($authors): foreach($authors as $key => $author): ?>
				<div class="authorbox<? if(bonus()):?> bonus<? endif; ?>">
					<? if(!bonus()): ?><a href="<?=site_url()?>author/<?=$author->authorid?>"><? endif; ?>
					<p class="articleauthor"><?=$author->authorname?></p>
					<? if(!bonus()): ?></a><? endif; ?>
					<p class="articleauthorjob"><?=$author->jobname?></p>
				</div>
			<? endforeach; endif; ?>
			<? if(bonus()): ?>
				<div class="authorbox bonus">
					<p class="articleauthor" id="addauthor" contenteditable="true" style="color:darkred">+</p>
					<p class="articleauthorjob" id="addauthorjob" contenteditable="true" style="color:red">+</p>
				</div>
			<? endif; ?>
			
			<div class="toolbox">
				
				<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?= current_url() ?>" data-via="bowdoinorient" data-lang="en">Tweet</a>
				<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
				<br/>
				
				<div class="fb-like" data-href="<?= current_url() ?>" data-send="false" data-layout="button_count" data-width="115" data-show-faces="false" data-action="recommend"></div>
				<br/>
				
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
					<button title="Your report is submitted to an independent third-party auditor, MediaBugs.">
						<img src="<?=base_url()?>images/reporterror-12-bw.png"><span class="buttontext"> Report error</span>
					</button>
				</a>
				
				<? if(bonus()): ?>
					Views: <?=$article->views?> (<?=$article->views_bowdoin?>)<br/>
				<? endif; ?>
				
			</div>
			
			<p class="articledate"><time pubdate datetime="<?=$article->date?>"><?=date("F j, Y",strtotime($article->date))?></time></p>
		</header>                
		
		<? if($photos): ?>
		<figure id="articlemedia">
			<? if(count($photos) == 1 || bonus()): ?>
				<? foreach($photos as $key => $photo): ?>
					<img src="<?=base_url()?>images/<?=$article->date?>/<?=$photo->filename_large?>">
					<figcaption>
						<p class="photocredit"><? if(!empty($photo->photographer_id)): ?><?= $photo->photographer_name ?><? else: ?><?= $photo->credit ?><? endif; ?></p>
						<p class="photocaption"><?=$photo->caption?></p>
						<? if(!bonus()):?><a href="http://pinterest.com/pin/create/button/?url=<?= urlencode(current_url()) ?>&media=<?= urlencode(base_url().'images/'.$article->date.'/'.$photo->filename_large) ?>&description=<?= urlencode(strip_tags($photo->caption)) ?>" class="pin-it-button hidemobile" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a><?endif;?>
					</figcaption>
				<? endforeach; ?>
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
				<figcaption>
					<p class="photocredit"<? if(count($photos) > 1): ?> style="margin-top: 0;text-shadow:none;color:gray;"<? endif;?>><? if(!empty($photos[0]->photographer_id)): ?><?= $photos[0]->photographer_name ?><? else: ?><?= $photos[0]->credit ?><? endif; ?></p>
					<p class="photocaption"><?=$photos[0]->caption?></p>
					<a href="http://pinterest.com/pin/create/button/?url=<?= urlencode(current_url()) ?>&media=<?= urlencode(base_url().'images/'.$article->date.'/'.$photos[0]->filename_large) ?>&description=<?= urlencode(strip_tags($photos[0]->caption)) ?>" class="pin-it-button hidemobile" count-layout="horizontal"><img border="0" src="//assets.pinterest.com/images/PinExt.png" title="Pin It" /></a>
				</figcaption>
			<? endif; ?>
		</figure>
		<? endif; ?>
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
		
		<? if(bonus()): ?>
			<figure id="bonus-meta">
				<ul id="bonus-tools">
					<li>Volume: <input type="text" name="volume" id="volume" size="2" value="<?=$article->volume?>" /></li>
					<li>Issue number: <input type="text" name="issue_number" id="issue_number" size="2" value="<?=$article->issue_number?>" /></li>
					<li>Section: <input type="text" name="section_id" id="section_id" size="2" value="<?=$article->section_id?>" /></li>
					<li>Priority: <input type="text" name="priority" id="priority" size="2" value="<?=$article->priority?>" /></li>
					<li>Published: <input type="checkbox" name="published" value="published" <? if($article->published): ?>checked="checked"<? endif; ?> /></li>
					<li>Featured: <input type="checkbox" name="featured" value="featured" <? if($article->featured): ?>checked="checked"<? endif; ?> /></li>
				</ul>
				<ul id="bonus-stats">
					<li>Pullquote: <br/><textarea rows="6" cols="30" name="pullquote" id="pullquote"><?=$article->pullquote?></textarea></li>
					<li><a href="#" class="delete" id="removephotos">Remove article photos</a></li>
					<li><a href="#" class="delete" id="deletearticle">Delete article</a></li>
				</ul>
			</figure>
		<? endif; ?>
		
		<div id="articlebody" class="articlebody"<?if(bonus()):?> contenteditable="true"<?endif;?>>
			<? if(!empty($body)): ?>
				<?=$body->body;?>
			<? else: ?>
				<?="<p>Enter article body here.</p>";?> 
			<? endif; ?>
		</div>
		
		<div id="articlefooter">
			
			<? if(!bonus()): ?>
			
				<!-- Disqus -->
				<div id="disqus_thread"></div>
				<script type="text/javascript">
					/* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
					var disqus_shortname = 'bowdoinorient'; // required: replace example with your forum shortname
					var disqus_title = '<?=addslashes($article->title)?>';
					
					//disqus_identifier isn't necessary, because it can use the URL. it's preferable, though, because of different URL schemes.
					//problem is, we used a different scheme (date&section&priority, e.g. 2012-05-04&2&1) on the old site.
					//on newer articles (>7308), we just use the new unique article id.
					<? if($article->id <= 7308): ?>
						var disqus_identifier = '<?=$article->date."?".$article->section_id."?".$article->priority?>';
					<? else: ?>
						var disqus_identifier = '<?=$article->id?>';
					<? endif; ?>
					
					/* * * DON'T EDIT BELOW THIS LINE * * */
					(function() {
						var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
						dsq.src = 'http://' + disqus_shortname + '.disqus.com/embed.js';
						(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
					})();
				</script>
				<noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
				<a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
			
			<? endif; ?>
			
		</div>
	  
	</article>

</div>

<? $this->load->view('bodyfooter', $footerdata); ?>

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
	<script type="text/javascript" src="<?= base_url() ?>js/swipeview-mwidmann.js"></script>
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