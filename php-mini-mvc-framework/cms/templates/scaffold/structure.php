<?php app()->begin_embedding('scaffold/main'); ?>
<?php

	// present structure and abilities to change it.
	
	echo Html::tag('h1', array(), $tableInfo['name']);
	
	echo Html::tag('h3', array(), 'Fields');
	echo '<table class="scaffold-entries">';
	echo Html::tableHeadingRow('cid', 'name', 'type', 'notnull', 'dflt_value', 'pk');
	foreach ($tableInfo['columns'] as $col)
		echo Html::tableRow($col['cid'], $col['name'], $col['type'], $col['notnull'], $col['dflt_value'], $col['pk']);
	echo '</table>';
	
	
	echo Html::tag('h3', array(), 'Indexes');
	if (!empty($tableInfo['indexes']))
		echo '<pre>'.var_export($tableInfo['indexes'], true);
	
	
	echo Html::tag('h3', array(), 'Foreign');
	if (!empty($tableInfo['foreign']))
		echo '<pre>'.var_export($tableInfo['foreign'], true);
	
	
?>
<?php app()->end_embedding(); ?>