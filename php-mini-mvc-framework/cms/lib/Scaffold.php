<?php

class Scaffold
{
	function __construct($baseRoute)
	{
		$this->baseRoute = $baseRoute;
	}
	
	var $baseRoute;
	var $recsPerPage = 10;
	
	public function run()
	{
		$tableName = array_key_exists('table', $_REQUEST) ? $_REQUEST['table'] : '';
		if (empty($tableName))
		{
			$this->listTables();
			return;
		}
		
		$tableInfo = $this->getTableInfo($tableName);
		$action = array_key_exists('action', $_REQUEST) ? $_REQUEST['action'] : 'list';
		
		if ($action == 'insert')
		{
			$this->insert($tableInfo);
		}
		else if ($action == 'view')
		{
			$this->view($tableInfo);
		}
		else if ($action == 'update')
		{
			$this->update($tableInfo);
		}
		else if ($action == 'delete')
		{
			$this->delete($tableInfo);
		}
		else if ($action == 'structure')
		{
			$this->structure($tableInfo);
		}
		else
		{
			$page = array_key_exists('page', $_REQUEST) ? (int)$_REQUEST['page'] : 1;
			$this->listRecords($tableInfo, $page);
		}
	}
	
	private function listTables()
	{
		$all = app()->db->directQuery('SELECT name FROM sqlite_master WHERE type = \'table\'');
		app()->log_debug('$all is ' . var_export($all, true));
		app()->load_template('scaffold/listTables', array(
			'baseRoute'=>$this->baseRoute,
			'tables'=>$all,
		));
	}
	
	private function listRecords($tableInfo, $page)
	{
		$count = app()->db->count($tableInfo['name']);
		$recs = app()->db->findAll($tableInfo['name'], array(
			'limit' => $this->recsPerPage,
			'offset' => ($page - 1) * $this->recsPerPage,
		));
		
		app()->load_template('scaffold/listRecords', array(
			'tableInfo'=>$tableInfo,
			'recs'=>$recs,
			'page'=>$page,
			'recsPerPage'=>$this->recsPerPage,
			'baseRoute'=>$this->baseRoute,
		));
	}
	
	private function insert($tableInfo)
	{
		$record = array();
		
		app()->load_template('scaffold/insert', array(
			'tableInfo'=>$tableInfo,
			'record'=>$record,
		));
	}
	
	private function view($tableInfo)
	{
		$record = $this->loadRecord($tableInfo);
		
		app()->load_template('scaffold/view', array(
			'tableInfo'=>$tableInfo,
			'record'=>$record,
		));
	}
	
	private function update($tableInfo)
	{
		$record = $this->loadRecord($tableInfo);
		
		app()->load_template('scaffold/update', array(
			'tableInfo'=>$tableInfo,
			'record'=>$record,
		));
	}
	
	private function delete($tableInfo)
	{
		if (isset($_POST[$tableInfo['name']]))
		{
		}
		
		app()->load_template('scaffold/delete', array(
			'rec'=>$rec,
			
		));
	}
	
	private function structure($tableInfo)
	{
		app()->load_template('scaffold/structure', array(
			'tableInfo'=>$tableInfo,
		));
	}
	
	
	
	private function getTableInfo($tableName)
	{
		$info = array();
		$info['name'] = $tableName;
		$info['columns'] = app()->db->directQuery('PRAGMA table_info('.$tableName.')');
		$info['indexes'] = app()->db->directQuery('PRAGMA index_list('.$tableName.')');
		$info['foreign'] = app()->db->directQuery('PRAGMA foreign_key_list('.$tableName.')');
		
		$info['pkFields'] = array();
		foreach ($info['columns'] as $column)
		{
			if ($column['pk'])
				$info['pkFields'][] = $column['name'];
		}
		
		return $info;
	}
	
	private function loadRecord($tableInfo)
	{
		$conditions = array();
		$params = array();
		
		foreach ($tableInfo['pkFields'] as $pkField)
		{
			$pkValue = array_key_exists($pkField, $_REQUEST) ? $_REQUEST[$pkField] : '';
			
			$conditions[] = $pkField.' = :'.$pkField;
			$params[':'.$pkField] = $pkValue;
		}
		
		return app()->db->find($tableInfo['name'], array(
			'condition'=>implode(' AND ', $conditions),
			'params'=>$params,
		));
	}
	
	
}


