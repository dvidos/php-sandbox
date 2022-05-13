<?php


/**
 * Class to help manipulate (more retrieve) data from sqlite database inside "cms/data/main.db"
 * Configuration parameters inside config.php
 */
class Criteria
{
	public $select = [];
	public $distinct = false;
	public $condition = '';
	public $orderBy = [];
	public $groupBy = [];
	public $having = '';
	public $limit = 0;
	public $offset = 0;
	public $pageSize = 0;
	public $pageNo = 0; // one based
	public $boundValues = [];
	
	public function __construct($condition = null, $boundValues = [])
	{
		$this->condition = $condition;
		$this->boundValues = $boundValues;
	}
	
	public function formatDistinct()
	{
		return empty($this->distinct) ? '' : ' DISTINCT';
	}
	
	public function formatSelect()
	{
		if (empty($this->select))
			return '*';
		else
			return $this->standardExpression('', $this->select, ', ');
	}
	
	public function formatCondition()
	{
		return $this->standardExpression('WHERE', $this->condition, ' AND ');
	}
	
	public function formatOrderBy()
	{
		return $this->standardExpression('ORDER BY ', $this->orderBy, ', ');
	}
	
	public function formatGroupBy()
	{
		return $this->standardExpression('GROUP BY ', $this->groupBy, ', ');
	}
	
	public function formatHaving()
	{
		return $this->standardExpression('HAVING ', $this->having, ' AND ');
	}
	
	public function formatLimits()
	{
		$limits = '';
		
		if ($this->limit)
		{
			$limits = ' LIMIT ' . $this->limit;
			if ($this->offset)
				$limits .= ' OFFSET ' . $this->offset;
		}
		else if ($this->pageSize && $this->pageNo)
		{
			$limits = ' LIMIT ' . $this->pageSize . ' OFFSET ' . (($this->pageNo - 1) * $this->pageSize);
		}
		
		return $limits;
	}
	
	
	public function bindValues(PDOStatement $statement)
	{
		if (is_array($this->boundValues))
		{
			foreach ($this->boundValues as $key => $value)
				$statement->bindValue($key, $value);
		}
	}
	
	protected function standardExpression($prefix, $property, $separator)
	{
		if (empty($property))
			return '';
		
		if (is_string($property) && strlen($property) > 0)
		{
			return ' ' . $prefix . ' ' . $property;
		}
		else if (is_array($property) && count($property) > 0)
		{
			$keys = array_keys($this->condition);
			$first_key = array_shift($keys);
			if (is_numeric($first_key))
			{
				return ' ' . $prefix . ' ' . implode($separator, $property);
			}
			else if (is_string($first_key))
			{
				// assoc array
				$keywords = [];
				foreach ($property as $key => $value)
				{
					$keywords[] = $key . ' = :' . $key;
					$this->boundValues[':' . $key] = $value;
				}
				return ' ' . $prefix . ' ' . implode($separator, $keywords);
			}
		}
		else
		{	
			return '';
		}
	}
}