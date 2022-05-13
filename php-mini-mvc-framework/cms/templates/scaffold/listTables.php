<?php app()->begin_embedding('scaffold/main'); ?>
<?php

$header = '';
$body = '';

$cells = '';
$cells .= Html::tag('th', array(), 'Πίνακας');
$cells .= Html::tag('th', array(), 'Εγγραφές');
$cells .= Html::tag('th', array(), 'Δομή');
$header .= Html::tag('tr', array(), $cells);


foreach ($tables as $table)
{
	$cells = '';
	$cells .= Html::tag('td', array(), $table['name']);
	$cells .= Html::tag('td', array(), Html::link('Εγγραφές', array('scaffold', 'table'=>$table['name'], 'action'=>'list')));
	$cells .= Html::tag('td', array(), Html::link('Δομή', array('scaffold', 'table'=>$table['name'], 'action'=>'structure')));
	$body .= Html::tag('tr', array(), $cells);
}

echo Html::tag('h1', array(), 'Πίνακες');
echo '<table class="scaffold-entries">';
echo '<thead>'.$header.'</thead>';
echo '<tbody>'.$body.'</tbody>';
echo '</table>';


?>
<?php app()->end_embedding(); ?>
