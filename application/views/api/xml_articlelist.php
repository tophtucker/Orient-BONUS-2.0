<?= '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<issue>

	<date><?=$issue_date; ?></date>
	<date_formatted><?=$issue->date; ?></date_formatted>
	<issue_number><?=$issue->issue_number; ?></issue_number>
	<volume_numeral><?=$issue->volume; ?></volume_numeral>
	<section><?=$section?></section>
	
	<? foreach($articles as $article): ?>
	
		<article id="<?=$article->priority ?>">
			<title><![CDATA[
<?=$article->title; ?>
]]></title>
			<author><?=$article->name; ?></author>
			<thumb><?php if(!empty($article->filename_small)) echo "http://bowdoinorient.com/images/".$issue_date."/".$article->filename_small; ?></thumb>
			<summary>
<![CDATA[
<?=$article->pullquote; ?>
]]></summary>
		</article>
		
	<? endforeach; ?>	

</issue>