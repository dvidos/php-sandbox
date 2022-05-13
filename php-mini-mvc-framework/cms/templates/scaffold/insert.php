<?php app()->begin_embedding('scaffold/main'); ?>
<?php

	echo Html::tag('h1', array(), $tableInfo['name'] . ': Νέα εγγραφή');
	
	app()->load_template('scaffold/_form', array(
		'newRecord'=>true,
		'tableInfo'=>$tableInfo,
		'record'=>$record,
	));
	
?>
<?php app()->end_embedding(); ?>