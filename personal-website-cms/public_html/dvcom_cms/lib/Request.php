<?php

class Request
{
	public $method = '';
	
	public function __construct()
	{
		$this->method = strtolower($_SERVER['REQUEST_METHOD']);
	}
	
	public function get_string($name, $max_size = 250, $default_value = '')
	{
		if (!isset($_REQUEST[$name]))
			return $default_value;
		
		$str = $_REQUEST[$name];
		$str = substr($str, 0, $max_size);
		
		return $str;
	}
	
	public function get_keyword($name, $default_value = '')
	{
		if (!isset($_REQUEST[$name]))
			return $default_value;
		
		$keyword = $_REQUEST[$name];
		$keyword = preg_replace('/[^a-zA-Z_0-9]/', '', $keyword);
		
		return $keyword;
	}
	
	public function get_int($name, $default_value = 0)
	{
		if (!isset($_REQUEST[$name]))
			return $default_value;

		$int = $_REQUEST[$name];
		$int = round((int)$int);

		return $int;
	}
	
	public function get_bool($name, $default_value = false)
	{
		if (!isset($_REQUEST[$name]))
			return $default_value;
		
		$bool = $_REQUEST[$name];
		$bool = strtolower(substr($bool, 0, 1));
		
		return ($bool == '1' || $bool == 'y' || $bool == 't');
	}
	
	public function get_html($name, $default_value = '')
	{
		if (!isset($_REQUEST[$name]))
			return $default_value;
		
		$html = $_REQUEST[$name];
		
		// should use something like html tidy here.
		return $html;
	}
	
	public function get_email($name)
	{
		if (!isset($_REQUEST[$name]))
			return '';
		
		$email = $_REQUEST[$name];
		return filter_var($email, FILTER_SANITIZE_EMAIL);
	}
	
	public function get_url($name)
	{
		if (!isset($_REQUEST[$name]))
			return '';
		
		$url = $_REQUEST[$name];
		return filter_var($url, FILTER_SANITIZE_URL);
	}
}