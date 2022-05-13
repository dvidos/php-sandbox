<?php


/**
 * Class to help manipulate data from mysql database
 * Configuration parameters inside config.php
 */
class Db
{
	public function __construct($dsn, $tables_prefix)
	{
		$this->dsn = $dsn;
		$this->tables_prefix = $tables_prefix;
	}
	
	protected $pdo = null;
	protected $dsn = '';
	protected $tables_prefix = '';

	public function connect($user, $pass)
	{
		$this->pdo = new PDO($this->dsn, $user, $pass);
	}
	
	public function find($table, $criteria = null)
	{
		// allow user to pass an array of conditions
		if (!empty($criteria) && is_array($criteria))
			$criteria = new Criteria($criteria);
		
		
		if (empty($criteria))
			$sql = 'SELECT * FROM ' . $this->prefixTablename($table) . ' LIMIT 1';
		else
			$sql = 'SELECT' . $criteria->formatSelect() . ' FROM ' . $this->prefixTablename($table) . $criteria->formatCondition() . ' LIMIT 1';
		
		$statement = $this->createStatement($sql, $criteria, array());
		if ($statement == null)
			return null;
		
		if (!$statement->execute())
			throw new Exception('Statement execute() failed: ' . $statement->errorInfo()[2]);
		
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		return (is_array($rows) && count($rows) > 0) ? $rows[0] : null;
	}
	
	public function findAll($table, Criteria $criteria = null)
	{
		// allow user to pass an array of conditions
		if (!empty($criteria) && is_array($criteria))
			$criteria = new Criteria($criteria);
		
		
		if (empty($criteria))
			$sql = 'SELECT * FROM ' . $this->prefixTablename($table);
		else
			$sql = 'SELECT' . 
				$criteria->formatDistinct() . 
				$criteria->formatSelect() . ' FROM ' . $this->prefixTablename($table) . '' .
				$criteria->formatCondition() . 
				$criteria->formatOrderBy() . 
				$criteria->formatGroupBy() . 
				$criteria->formatHaving() . 
				$criteria->formatLimits();

		$statement = $this->createStatement($sql, $criteria, array());
		if ($statement == null)
			return null;
		
		if (!$statement->execute())
			throw new Exception('Statement execute() failed: ' . $statement->errorInfo()[2]);
		
		return $statement->fetchAll(PDO::FETCH_ASSOC);
	}
	
	public function count($table, Criteria $criteria = null)
	{
		return $this->aggregate($table, 'COUNT(*)', $criteria);
	}
	
	public function avg($table, $field, Criteria $criteria = null)
	{
		return $this->aggregate($table, 'AVG(' . $field . ')', $criteria);
	}
	
	public function min($table, $field, Criteria $criteria = null)
	{
		return $this->aggregate($table, 'MIN(`' . $field . '`)', $criteria);
	}
	
	public function max($table, $field, Criteria $criteria = null)
	{
		return $this->aggregate($table, 'MAX(`' . $field . '`)', $criteria);
	}
	
	public function sum($table, $field, Criteria $criteria = null)
	{
		return $this->aggregate($table, 'SUM(`' . $field . '`)', $criteria);
	}
	
	protected function aggregate($table, $func, Criteria $criteria)
	{
		$sql = 'SELECT ' . $func . ' AS aggr FROM ' . $this->prefixTablename($table);
		if (!empty($criteria))
			$sql .= ' ' . $criteria->formatCondition();
		
		$statement = $this->createStatement($sql, $criteria, array());
		if ($statement == null)
			return null;
		
		if (!$statement->execute())
			throw new Exception('Statement execute() failed: ' . $statement->errorInfo()[2]);
		
		$rows = $statement->fetchAll(PDO::FETCH_ASSOC);
		return (is_array($rows) && count($rows) > 0) ? $rows[0]['aggr'] : false;
	}
	
	public function insert($table, $values)
	{
		$sql = 'INSERT INTO `' . $this->prefixTablename($table) . '` ' .
			'(`' . implode('`, `', array_keys($values)) . '`)' .
			' VALUES ' . 
			'(:' . implode(', :', array_keys($values)) . ')';
		
		$statement = $this->createStatement($sql, null, $values);
		if ($statement == null)
			return false;
		
		if (!$statement->execute())
			throw new Exception('Statement execute() failed: ' . $statement->errorInfo()[2]);
	}
	
	public function update($table, $values, $criteria)
	{
		// allow user to pass an array of conditions
		if (!empty($criteria) && is_array($criteria))
			$criteria = new Criteria($criteria);
		
		
		$sets = array();
		foreach ($values as $key => $val)
			$sets[] = '`' . $key . '` = :' . $key;

		$sql = 'UPDATE `' . $this->prefixTablename($table) . '` SET ' . implode(', ', $sets);
		if (!empty($criteria))
			$sql .= $criteria->formatCondition();
		
		$statement = $this->createStatement($sql, $criteria, $values);
		if ($statement == null)
			return false;
		
		if (!$statement->execute())
			throw new Exception('Statement execute() failed: ' . $statement->errorInfo()[2]);
	}
	
	public function delete($table, Criteria $criteria = null)
	{
		// allow user to pass an array of conditions
		if (!empty($criteria) && is_array($criteria))
			$criteria = new Criteria($criteria);
		
		
		$sql = 'DELETE FROM ' . $this->prefixTablename($table);
		if (!empty($criteria))
			$sql .= $criteria->formatCondition();
		
		$statement = $this->createStatement($sql, $criteria, $values);
		if ($statement == null)
			return false;
		
		if (!$statement->execute())
			throw new Exception('Statement execute() failed: ' . $statement->errorInfo()[2]);
	}
	
	public function last_insert_id()
	{
		if ($this->pdo == null)
			return null;
		
		return $this->pdo->lastInsertId();
	}
	
	
	
	
	protected function createStatement($sql, Criteria $criteria = null, $values = null)
	{
		if ($this->pdo == null)
			return null;
		
		$statement = $this->pdo->prepare($sql);
		if ($statement == null)
			return null;
		
		if (!empty($values))
		{
			foreach ($values as $name => $value)
				$statement->bindValue(':' . $name, $value);
		}
		
		if (!empty($criteria))
			$criteria->bindValues($statement);
		
		return $statement;
	}
	
	protected function prefixTablename($name)
	{
		return empty($this->tables_prefix) ? $name : $this->tables_prefix . $name;
	}

	public function escape_string($str)
	{
		if ($this->pdo == null)
			return null;

		return $this->pdo->quote($str);
	}

	public function show_create_table($table)
	{
		$sql = 'SHOW CREATE TABLE ' . $this->prefixTablename($table);

		$statement = $this->createStatement($sql);
		if ($statement == null)
			return null;

		if (!$statement->execute())
			throw new Exception('Statement execute() failed: ' . $statement->errorInfo()[2]);

		$row = $statement->fetch(PDO::FETCH_ASSOC);
		if (empty($row) || empty($row['Create Table']))
			return '';

		return $row['Create Table'];
	}
}