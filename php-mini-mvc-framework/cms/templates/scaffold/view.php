<?php app()->begin_embedding('scaffold/main'); ?>
<?php

	echo Html::tag('h1', array(), $tableInfo['name'] . ': View Record');
	
	echo '<table class="scaffold-entries">';
	echo Html::tableHeadingRow('Field', 'Content');
	foreach ($tableInfo['columns'] as $col)
		echo Html::tableRow($col['name'], Html::encode($record[$col['name']]));
	echo '</table>';
	
?>
<?php app()->end_embedding(); ?>