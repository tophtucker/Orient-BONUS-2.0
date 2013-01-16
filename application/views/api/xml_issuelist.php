<?= '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<volume>
	<volumenumber><?=$volume_arabic; ?></volumenumber>
	
	<? foreach($issues as $issue): ?>
		
	<issue id="<?=$issue->issue_number; ?>">
		<date><?=$issue->date; ?></date>
	</issue>
	
	<? endforeach; ?>
	
</volume>