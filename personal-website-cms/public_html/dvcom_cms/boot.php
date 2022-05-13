<?php

// one global variable
$cms = null;

// boot inside a function to avoid polluting the global namespace
boot();

function boot()
{
	global $cms;
	
	// by definition, CMS_DIR is the directory of this file
	// no trailing slash
	$cms_path = str_replace('\\', '/', dirname(__FILE__));

	// load autoloader
	require $cms_path . '/lib/Autoloader.php';
	Autoloader::$root_path = $cms_path;
	Autoloader::register();

	$cms = new Cms();
	$cms->path = $cms_path;

	// load common config
	$cms->config = require $cms->path . '/config/common.php';

	// find environment
	$cms->env = new Environment();
	$cms->env->detectEnvironment($cms->config);
	$cms->env->setErrorReporting($cms->config['show_errors']);
	session_start();


	// load environment config
	$env_config = require $cms->path . '/config/' . $cms->env->keyword . '.php';
	$cms->config = array_merge($cms->config, $env_config);

	// create request object
	$cms->request = new Request();
	
	// load database
	$cms->db = new Db($cms->config['db']['dsn'], $cms->config['db']['tables_prefix']);
	$cms->db->connect($cms->config['db']['user'], $cms->config['db']['pass']);
	
	try
	{
		// determine application, load application, controller, act.
		$cms->dispatcher = new Dispatcher($cms);
		$cms->dispatcher->dispatch();
		
	}
	catch (HttpException $e)
	{
		http_response_code($e->code);
		echo $e->code . ' ' . $e->message;
		exit(1);
	}
	catch (Exception $e)
	{
		http_response_code(400);
		echo 'Exception ' . $e->getMessage();
		echo '<br />';
		exit(1);
	}
}

function cms()
{
	global $cms;
	return $cms;
}