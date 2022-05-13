<?php

class Article extends Model
{
	public function __construct()
	{
		$this->tablename = 'articles';
		
		// default fields.
		$this->id = 0;
		$this->title = '';
		$this->content = '';
		$this->published = 0;
		$this->created_at = date('Y-m-d H:i:s');
		$this->updated_at = '';
		$this->publish_at = '';
	}
}