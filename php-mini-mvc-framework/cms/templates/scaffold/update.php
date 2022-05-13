<?php app()->begin_embedding('scaffold/main'); ?>
<?php
	
	echo Html::tag('h1', array(), $tableInfo['name'] . ': Διόρθωση');
	
	app()->load_template('scaffold/_form', array(
		'newRecord'=>false,
		'tableInfo'=>$tableInfo,
		'record'=>$record,
	));
	
?>
<?php app()->end_embedding(); ?>