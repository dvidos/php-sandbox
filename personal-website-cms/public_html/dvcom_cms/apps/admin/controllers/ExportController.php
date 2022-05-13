<?php

class ExportController extends Controller
{
	function actionIndex()
	{
		echo cms()->load_view('index');
	}
	
	
	function actionExport()
	{
		
		if (empty($_POST['export']))
			return;
		
		$vars = [
			'tables_rows' => [
				'users' => cms()->db->findAll('users'),
				'articles' => cms()->db->findAll('articles'),
			],
			'test' => '123',
		];
		
		$this->template = 'empty';
		$filename = $_SERVER['SERVER_NAME'] . ' export ' . date('Y-m-d H:i:s') . '.sql';
		header('Content-type: text/plain');
		header('Content-Disposition: inline; filename="' . $filename . '"');
		echo cms()->load_view('export', $vars);
	}
}

