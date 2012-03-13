<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<title>iScroll demo: Carousel</title>

<script type="text/javascript" src="<?=base_url()?>js/iscroll.js"></script>

<script type="text/javascript">
var myScroll;

function loaded() {
	myScroll = new iScroll('iscroll_wrapper', {
		snap: 'li',
		momentum: false,
		hScrollbar: false,
		vScroll: false,
		onScrollEnd: function () {
			document.querySelector('#iscroll_indicator > li.active').className = '';
			document.querySelector('#iscroll_indicator > li:nth-child(' + (this.currPageX+1) + ')').className = 'active';
		}
	 });
	 
	
	var interval = setInterval(function () { 
			if (myScroll.currPageX == myScroll.pagesX.length-1) myScroll.scrollToPage(0, 0, 1000); 
			else myScroll.scrollToPage('next', 0, 400); 
		}, 5000); 
	
}

document.addEventListener('DOMContentLoaded', loaded, false);

</script>

<style type="text/css" media="all">


#iscroll_wrapper {
	width:300px;
	height:100%;

	float:left;
	position:relative;	/* On older OS versions "position" and "z-index" must be defined, */
	z-index:1;			/* it seems that recent webkit is less picky and works anyway. */
	overflow:hidden;

	background:#aaa;
	background:#e3e3e3;
}

#iscroll_wrapper img {
	width: 100%;
}

#iscroll_scroller {
	width:2100px;
	height:100%;
	float:left;
	padding:0;
}

#iscroll_scroller ul {
	list-style:none;
	display:block;
	float:left;
	width:100%;
	height:100%;
	padding:0;
	margin:0;
	text-align:left;
}

#iscroll_scroller li {
	-webkit-box-sizing:border-box;
	-moz-box-sizing:border-box;
	-o-box-sizing:border-box;
	box-sizing:border-box;
	display:block; float:left;
	width:300px; height:100%;
	text-align:center;
	font-family:georgia;
	font-size:18px;
	line-height:140%;
}

#iscroll_nav {
	width:300px;
	float:left;
}

#iscroll_prev, #iscroll_next {
	float:left;
	font-weight:bold;
	font-size:14px;
	padding:5px 0;
	width:80px;
}

#iscroll_next {
	float:right;
	text-align:right;
}

#iscroll_indicator, #iscroll_indicator > li {
	display:block; float:left;
	list-style:none;
	padding:0; margin:0;
}

#iscroll_indicator {
	width:110px;
	padding:12px 0 0 30px;
}

#iscroll_indicator > li {
	text-indent:-9999em;
	width:8px; height:8px;
	background:#ddd;
	overflow:hidden;
	margin-right:4px;
}

#iscroll_indicator > li.active {
	background:#888;
}

#iscroll_indicator > li:last-child {
	margin:0;
}

</style>
</head>
<body>
<div id="iscroll_wrapper">
	<div id="iscroll_scroller">
		<ul id="thelist">
			<? foreach($photos as $photo): ?>
			<li><img src="<?=base_url()?>images/<?=$article->date?>/<?=$photo->filename_large?>"></li>
			<? endforeach; ?>
		</ul>
	</div>
</div>
<div id="iscroll_nav">
	<div id="iscroll_prev" onclick="myScroll.scrollToPage('prev', 0);return false">&larr; prev</div>
	<ul id="iscroll_indicator">
		<? foreach($photos as $key => $photo): ?>
		<li<? if($key==0): ?> class="active"<? endif; ?>><?= ($key+1) ?></li>
		<? endforeach; ?>
	</ul>
	<div id="iscroll_next" onclick="myScroll.scrollToPage('next', 0);return false">next &rarr;</div>
</div>
</body>
</html>