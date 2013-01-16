<?= '<?xml version="1.0" encoding="UTF-8" ?>'; ?>

<orient>
	
	<? foreach($volumes as $volume): ?>
	
	<volume id="<?=$volume->id; ?>">
		<numeral><?=$volume->roman; ?></numeral>
	</volume>
	
	<? endforeach; ?>
	
</orient>