<?php

class Model
{
	protected $tablename = '';
	protected $id_fieldname = 'id';
	
	
	protected $properties = [];
	protected $eventHandlers = [];
	
	
	
	public function __get($name)
	{
		if (array_key_exists($name, $this->properties))
			return $this->properties[$name];
		
		throw new Exception(__CLASS__ . ': Property ' . $name . ' not found');
	}
	
	public function __set($name, $value)
	{
		// could validate here.
		$this->properties[$name] = $value;
	}
	
	public function __isset($name)
	{
		return isset($this->properties[$name]);
	}
	
	public function __unset($name)
	{
		unset($this->properties[$name]);
	}
	
	public function populate($values_assoc)
	{
		foreach ($values_assoc as $name => $value)
			$this->$name = $value;
	}
	
	
	
	public function find($criteria)
	{
		if (is_int($criteria) || is_string($criteria))
			$criteria = [$this->id_fieldname => $criteria];
		
		$row = cms()->db->find($this->tablename, $criteria);
		if (empty($row))
			return false;
		
		$this->populate($row);
		return true;
	}
	
	public function findAll($criteria = null)
	{
		$models = [];
		
		$rows = cms()->db->findAll($this->tablename, $criteria);
		foreach ($rows as $row)
		{
			$model = new static();
			$model->populate($row);
			$models[] = $model;
		}
		
		return $models;
	}
	
	public function count(Criteria $criteria = null)
	{
		return cms()->db->count($this->tablename, $criteria);
	}
	
	public function isNewRecord()
	{
		return (empty($this->properties[$this->id_fieldname]));
	}
	
	public function save()
	{
		if ($this->isNewRecord())
		{
			$ok = cms()->db->insert($this->tablename, $this->properties);
			if (!$ok)
				return false;
			
			$this->id_fieldname = cms()->db->last_insert_id();
		}
		else
		{
			return cms()->db->update($this->tablename, $this->properties, [$this->id_fieldname => $this->properties[$this->id_fieldname]]);
		}
	}
	
	public function delete()
	{
		if ($this->isNewRecord())
			return;
		
		return cms()->db->delete($this->tablename, [$this->id_fieldname => $this->properties[$this->id_fieldname]]);
	}
	
	
	
	
	
	public function onEvent($name, $callable)
	{
		if (!array_key_exists($name, $this->eventHandlers))
			$this->eventHandlers[$name] = [];
		
		$this->eventHandlers[$name][] = $callable;
	}
	
	public function offEvent($name, $callable)
	{
		if (!array_key_exists($name, $this->eventHandlers))
			return;
		
		if (!in_array($callable, $this->eventHandlers[name]))
			return;
		
		$newHandlers = [];
		foreach ($this->eventHandlers as $handler)
			if ($handler != $callable)
				$newHandlers[] = $handler;
		$this->eventHandlers[$name] = $newHandlers;
	}
	
	protected function triggerEvent($name, $arguments)
	{
		if (!array_key_exists($name, $this->eventHandlers))
			return;
		
		foreach ($this->eventHandlers[$name] as $callable)
		{
			call_user_func($callable, $arguments);
		}
	}
}