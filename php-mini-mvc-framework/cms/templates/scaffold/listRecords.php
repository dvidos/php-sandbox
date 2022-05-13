<?php app()->begin_embedding('scaffold/main'); ?>
<?php



function createActionLink($caption, $tableInfo, $baseRoute, $action, $record)
{
	$url = array(
		$baseRoute, 
		'table'=>$tableInfo['name'],
		'action'=>$action
	);
	foreach ($tableInfo['pkFields'] as $pkField)
		$url[$pkField] = $record[$pkField];
	
	return Html::link($caption, $url);
}




// list $recs

$max_columns = 4;
$header = '';
$body = '';



$cells = '';
for ($c = 0; $c < min($max_columns, count($tableInfo['columns'])); $c++)
{
	$col = $tableInfo['columns'][$c];
	$cells .= Html::tag('th', array(), Html::encode($col['name']));
}
$cells .= Html::tag('th', array(), 'Ενέργειες');
$header .= Html::tag('tr', array(), $cells);




for ($i = 0; $i < count($recs); $i++)
{
	$rec = $recs[$i];
	$cells = '';
	
	for ($c = 0; $c < min($max_columns, count($tableInfo['columns'])); $c++)
	{
		$col = $tableInfo['columns'][$c];
		$name = $col['name'];
		$cells .= Html::tag('td', array(), Html::encode($rec[$name]));
	}
	
	
	// TODO: move strings to cms/locale/xx_xx.php
	$actions = '';
	$actions .= createActionLink('Εμφ', $tableInfo, $baseRoute, 'view', $rec) . ' ';
	$actions .= createActionLink('Διορθ', $tableInfo, $baseRoute, 'update', $rec) . ' ';
	$actions .= createActionLink('Διαγρ', $tableInfo, $baseRoute, 'delete', $rec);
	$cells .= Html::tag('td', array(), $actions);
	
	$body .= Html::tag('tr', array(), $cells);
}

$newRecord = Html::link('Νέα εγγραφή', array($baseRoute, 'table'=>$tableInfo['name'], 'action'=>'insert'));

echo Html::tag('h1', array(), $tableInfo['name']);
echo Html::tag('p', array(), $newRecord);
echo '<table class="scaffold-entries">';
echo '<thead>'.$header.'</thead>';
echo '<tbody>'.$body.'</tbody>';
echo '</table>';

$pages = ceil(app()->db->count($tableInfo['name']) / $recsPerPage);
echo Html::pagination(array($baseRoute, 'table'=>$tableInfo['name']), $page, $pages);


?>
<?php app()->end_embedding(); ?>