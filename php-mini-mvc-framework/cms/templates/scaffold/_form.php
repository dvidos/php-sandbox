<?php
	foreach ($tableInfo['columns'] as $col)
	{
		$colName = $col['name'];
		
		echo '<p>';
		echo Html::tag('label', array('for'=>'record_' . $colName), $colName);
		echo '<br />';
		echo Html::tag('input', array('id'=>'record_' . $colName, 'name'=>'record['.$colName.']', 'type'=>'text', 'value'=>$record[$colName]));
		echo '</p>';
	}
	
	$caption = $newRecord ? 'Insert' : 'Update';
	echo Html::tag('input', array('type'=>'submit', 'value'=>$caption, 'id'=>'record_submit'));
?>