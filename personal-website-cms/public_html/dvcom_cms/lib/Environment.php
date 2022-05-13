<?php


class Environment
{
	public $keyword = '';
	public $is_dev = false;
	public $is_test = false;
	public $is_prod = false;
	
	public function detectEnvironment($config)
	{
		if (empty($config['dev_hosts']) || !is_array($config['dev_hosts']))
			die('dev_hosts not defined in config');
		
		if (empty($config['test_hosts']) || !is_array($config['test_hosts']))
			die('test_hosts not defined in config');
		
		if (empty($config['prod_hosts']) || !is_array($config['prod_hosts']))
			die('prod_hosts not defined in config');
		
		$host = $_SERVER['HTTP_HOST'];
		if (in_array($host, $config['dev_hosts'])) {
		
			$this->keyword = 'dev';
			$this->is_dev = true;
			
		} else if (in_array($host, $config['test_hosts'])) {

			$this->keyword = 'test';
			$this->is_test = true;
			
		} else if (in_array($host, $config['prod_hosts'])) {
			
			$this->keyword = 'prod';
			$this->is_prod = true;
			
		} else {
			
			die('Cannot detect environment');
			
		}
	}
	
	public function setErrorReporting($show_errors)
	{
		if ($show_errors)
		{
			error_reporting(-1);
			ini_set('display_errors', 1);
		}
		else
		{
			error_reporting(0);
			ini_set('display_errors', 0);
		}
	}
}
