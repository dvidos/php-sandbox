<?php

class ActiveRecord
{
	// table name
	var $table_name;
	
	// table caption eg "Προϊόντα"
	var $table_caption;
	
	// fields captions (associative)
	var $fields_captions;
	
	// fields used for record identification
	var $id_fields;
	
	// fields used for record caption
	var $caption_fields;
	
	// default columns for list
	var $list_fields;
	
	// default order for list
	var $list_order;
	
	// associative
	var $values;
	
	// associative
	var $original_id_values;
	
	// name of auto-incremented field
	var $auto_incremented_field;
	
	
	
	
	
	// find and load a record. 
	// criteria can be auto-incremented number, a condition, or an array
	public function find($criteria)
	{
		if (!empty($this->auto_incremented_field) && is_int($criteria))
			$criteria = '`' . $this->auto_incremented_field . '` = ' . $criteria;
		
		$rec = app()->db->find($this->table_name, $criteria);
		if (!is_array($rec))
			return false;

		$this->original_id_values = array();
		foreach ($this->id_fields as $id_field)
			$this->original_id_values[$id_field] = $rec[$id_field];
		$this->values = $rec;
		
		// could validate our values
		
		$this->afterFind();
		return true;
	}
	
	protected function afterCreate()
	{
		// default values for fields
	}
	
	protected function afterFind()
	{
		// called after loading a record
	}
	
	
	
	public function insert()
	{
		$this->beforeInsert();
		
		app()->db->insert($this->table_name, $this->values);
		$this->getAutoIncrement();
		
		$this->afterInsert();
	}
	
	public function update()
	{
		$this->beforeUpdate();
		
		$criteria = $this->createOriginalIdCriteria();
		app()->db->update($this->table_name, $this->values, $criteria);
		
		$this->afterUpdate();
	}
	
	public function delete()
	{
		if (!$this->isNewRecord())
		{
			$this->beforeDelete();
			
			$criteria = $this->createOriginalIdCriteria();
			app()->db->delete($this->table_name, $criteria);
			
			$this->afterDelete();
		}
	}
	
	public function save()
	{
		if ($this->isNewRecord())
			$this->insert();
		else
			$this->update();
	}
	
	
	
	protected function beforeInsert()
	{
	}
	
	protected function afterInsert()
	{
	}
	
	protected function beforeUpdate()
	{
	}
	
	protected function afterUpdate()
	{
	}
	
	protected function beforeDelete()
	{
	}
	
	protected function afterDelete()
	{
	}
	
	
	
	public function isNewRecord()
	{
		return empty($this->original_id_values);
	}
	
	protected function updateAutoIncrement()
	{
		// called after insert to reflect the new id
		if (!empty($this->auto_incremented_field))
		{
			$id = app()->db->lastInsertId();
			if (empty($this->original_id_values))
				$this->original_id_values = array();
			$this->original_id_values[$this->auto_incremented_field] = $id;
		}
	}
	
	protected function createOriginalIdCriteria()
	{
		$conditions = array();
		$params = array();
		foreach ($this->id_fields as $id_field)
		{
			$conditions[] = '`' . $id_field . '` = :' . $id_field;
			$params[':' . $id_field] = $this->original_id_values[$id_field];
		}
		
		return array(
			'condition' => implode(' AND ', $conditions),
			'params'=>$params,
		);
	}
	
	
	
	
}


