	<footer id="bodyfooter">
		
		<div id="vcard" class="vcard">
			<a class="fn org url" href="http://orient.bowdoin.edu" title="The Bowdoin Orient"><span class="organization-name">The Bowdoin Orient</span></a><br>
			<span class="adr">
				<span class="email"><a href="mailto:orient@bowdoin.edu">orient@bowdoin.edu</a></span><br>
				<span class="tel"><span class="value">(207) 725-3300</span></span><br>
				<span class="street-address">6200 College Station</span><br>
				<span class="locality">Brunswick</span>, <span class="region">Maine</span> <span class="postal-code">04011</span><br>
			</span>
			<div id="copyright"><small>&copy; <?=date("Y")?>, The Bowdoin Orient</a>. &ndash;30&ndash;</small></div>
		</div>
		
		<div id="footerlinks">
			<ul>
				<li><a href="<?=site_url()?>about">About</a></li>
				<!--<li><a href="<?=site_url()?>archives">Archives</a></li>-->
				<li><a href="<?=site_url()?>subscribe">Subscribe</a></li>
				<li><a href="<?=site_url()?>advertise">Advertise</a></li>
			</ul>
		</div>
		
		<div id="bonusquoteblock">
			<div class="bonusquote">&ldquo;<?=$quote->quote?>&rdquo;</div>
			<? if(!empty($quote->attribution)): ?><div class="bonusquoteattribution">&mdash; <?=$quote->attribution?><? if(!empty($quote->source)): ?> <a href="<?=$quote->source?>">↬</a><? endif; ?></div><? endif; ?>
		</div>
		
	</footer>
