<?php


/**
 * Class to help manipulate (more retrieve) data from sqlite database inside "cms/data/main.db"
 * Configuration parameters inside config.php
 */
class Db
{
	function __construct()
	{
		// connect
		if (!empty(app()->config['db_pdo_dsn']))
		{
			app()->log_info('Creating PDO using dsn: ' . app()->config['db_pdo_dsn']);
			$this->pdo = new PDO(app()->config['db_pdo_dsn'], app()->config['db_pdo_user'], app()->config['db_pdo_pass']);
		}
	}
	
	var $pdo = null;
	
	
	
	public function find($table, $criteria = array())
	{
		if (!is_string($table) || empty($table))
			throw new Exception('Expecting table name as first argument');
		
		$sql = 'SELECT ' . $this->formatSelectFields($criteria) . ' FROM `' . $table . '`' . $this->formatCondition($criteria) . ' LIMIT 1';
		$statement = $this->createStatement($sql, $criteria, array());
		if ($statement == null)
			return null;
		
		$statement->execute();
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		return (is_array($rows) && count($rows) > 0) ? $rows[0] : null;
	}
	
	public function findAll($table, $criteria = array())
	{
		if (!is_string($table) || empty($table))
			throw new Exception('Expecting table name as first argument');
		
		$sql = 'SELECT ' . 
			$this->formatDistinct($criteria) . 
			$this->formatSelectFields($criteria) . ' FROM `' . $table . '`' .
			$this->formatCondition($criteria) . 
			$this->formatOrder($criteria) . 
			$this->formatGroup($criteria) . 
			$this->formatLimits($criteria);

		$statement = $this->createStatement($sql, $criteria, array());
		if ($statement == null)
			return null;
		
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function count($table, $criteria = array())
	{
		return $this->aggregate($table, 'count(*)', $criteria);
	}
	
	public function avg($table, $field, $criteria = array())
	{
		return $this->aggregate($table, 'AVG(`' . $field . '`)', $criteria);
	}
	
	public function min($table, $field, $criteria = array())
	{
		return $this->aggregate($table, 'MIN(`' . $field . '`)', $criteria);
	}
	
	public function max($table, $field, $criteria = array())
	{
		return $this->aggregate($table, 'MAX(`' . $field . '`)', $criteria);
	}
	
	public function sum($table, $field, $criteria = array())
	{
		return $this->aggregate($table, 'SUM(`' . $field . '`)', $criteria);
	}
	
	protected function aggregate($table, $func, $criteria)
	{
		if (!is_string($table) || empty($table))
			throw new Exception('Expecting table name as first argument');
		
		$sql = 'SELECT ' . $func . ' AS aggr FROM `' . $table . '`' . $this->formatCondition($criteria);
		
		$statement = $this->createStatement($sql, $criteria, array());
		if ($statement == null)
			return null;
		
		$statement->execute();
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		$aggr = (is_array($rows) && count($rows) > 0) ? $rows[0]['aggr'] : false;
		app()->log_debug('--> ' . $aggr);
		return $aggr;
	}
	
	public function insert($table, $values)
	{
		$sql = 'INSERT INTO `' . $table . '` ' .
			'(`' . implode('`, `', array_keys($values)) . '`)' .
			' VALUES ' . 
			'(:' . implode(', :', array_keys($values)) . ')';
		
		$statement = $this->createStatement($sql, array(), $values);
		if ($statement == null)
			return false;
		
		return $statement->execute();
	}
	
	public function update($table, $values, $criteria = array())
	{
		$sets = array();
		foreach ($values as $key => $val)
			$sets[] = '`' . $key . '` = :' . $key;

		$sql = 'UPDATE `' . $table . '` SET ' . implode(', ', $sets) . $this->formatCondition($criteria);
		
		$statement = $this->createStatement($sql, $criteria, $values);
		if ($statement == null)
			return false;
		
		return $statement->execute();
	}
	
	public function delete($table, $criteria = array())
	{
		$sql = 'DELETE FROM `' . $table . '`' . $this->formatCondition($criteria);
		
		$statement = $this->createStatement($sql, $criteria, $values);
		if ($statement == null)
			return false;
		
		return $statement->execute();
	}
	
	
	
	public function lastInsertId()
	{
		$lid = $this->pdo->lastInsertId();
		app()->log_debug('last insert id: ' . $lid);
		return $lid;
	}
	
	
	public function directExecute($sql)
	{
		$statement = $this->createStatement($sql, null, null);
		if ($statement == null)
			return false;
		
		return $statement->execute();
	}
	public function directQuery($sql)
	{
		$statement = $this->createStatement($sql, null, null);
		if ($statement == null)
			return false;
		
		$statement->execute();
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	
	
	
	
	
	protected function formatDistinct($criteria)
	{
		if (empty($criteria) || !is_array($criteria) || !array_key_exists('distinct', $criteria) || !$criteria['distinct'])
			return '';
			
		return ' DISTINCT';
	}
	
	protected function formatSelectFields($criteria)
	{
		if (empty($criteria) || !is_array($criteria) || !array_key_exists('fields', $criteria) || empty($criteria['fields']))
			return '*';
		
		if (is_array($criteria['fields']))
			return '`' . implode('`, `', $criteria['fields']) . '`';
		else if (is_string($criteria['fields']))
			return $criteria['fields'];
		else
			return '*';
	}
	
	protected function formatCondition($criteria)
	{
		//	a string format is treated as condition
		if (is_string($criteria) && !empty($criteria))
			return ' WHERE ' . $criteria;
		
		// an array may have a condition element.
		if (is_array($criteria) && array_key_exists('condition', $criteria) && !empty($criteria['condition']))
			return ' WHERE ' . $criteria['condition'];

		return '';
	}
	
	protected function formatOrder($criteria)
	{
		if (empty($criteria) || !is_array($criteria) || !array_key_exists('order', $criteria) || empty($criteria['order']))
			return '';
		
		if (is_array($criteria['order']))
			return ' ORDER BY `' . implode('`, `', $criteria['order']) . '`';
		else if (is_string($criteria['order']))
			return ' ORDER BY ' . $criteria['order'];
		else
			return '';
	}
	
	protected function formatGroup($criteria)
	{
		if (empty($criteria) || !is_array($criteria) || !array_key_exists('groupBy', $criteria) || empty($criteria['groupBy']))
			return '';
		
		$group = ' GROUP BY ' . $criteria['groupBy'];
		
		if (array_key_exists('having', $criteria) && !empty($criteria['having']))
			$group .= ' HAVING ' . $criteria['having'];
		
		return $group;	
	}
	
	protected function formatLimits($criteria)
	{
		if (empty($criteria) || !is_array($criteria) || !array_key_exists('limit', $criteria) || empty($criteria['limit']))
			return '';
		
		$limits = ' LIMIT ' . ($criteria['limit'] * 1);
		
		if (array_key_exists('offset', $criteria) && !empty($criteria['offset']))
			$limits .= ' OFFSET ' . ($criteria['offset'] * 1);
		
		return $limits;	
	}

	protected function createStatement($sql, $criteria, $values)
	{
		if ($this->pdo == null)
			return null;
		
		app()->log_debug('preparing statement: "' . $sql . '"');
		$statement = $this->pdo->prepare($sql);
		if ($statement == null)
			return null;
		
		if (is_array($criteria) && array_key_exists('params', $criteria) && !empty($criteria['params']))
			foreach ($criteria['params'] as $key => $val)
				$statement->bindParam($key, $val);
		
		if (is_array($values) && !empty($values))
			foreach ($values as $key => $val)
				$statement->bindParam(':' . $key, $val);
				
		return $statement;
	}
}