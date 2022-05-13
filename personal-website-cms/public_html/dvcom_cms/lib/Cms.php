<?php

class Cms
{
	// the root path of the cms, where lib and applications reside
	public $path = '';
	
	// the Environment class
	public $env;
	
	// the common config merged with environment config
	public $config = [];
	
	// the current request
	public $request;
	
	// the dispatcher object
	public $dispatcher;
	
	// the current application
	public $app;
	
	// the current application name
	public $app_name;
	
	// the current controller
	public $controller;
	
	// the current controller name
	public $controller_name;

	// the current action name (i.e. "update")
	public $action_name;
	
	// the database access
	public $db;
	
	// redirect url for after run action
	public $redirect_url;
	
	
	public function application_exists($name)
	{
		$file = $this->path . '/apps/' . $name . '/' . ucfirst($name) . 'Application.php';
		return file_exists($file);
	}
	
	public function load_application($name)
	{
		$file = $this->path . '/apps/' . $name . '/' . ucfirst($name) . 'Application.php';
		require $file;
		
		$class = ucfirst($name) . 'Application';
		$app = new $class();
		
		$this->app = $app;
		$this->app_name = $name;
		return $app;
	}
	
	public function controller_exists($app_name, $controller_name)
	{
		$file = $this->path . '/apps/' . $app_name . '/controllers/' . ucfirst($controller_name) . 'Controller.php';
		return file_exists($file);
	}
	
	public function load_controller($app_name, $controller_name)
	{
		$file = $this->path . '/apps/' . $app_name . '/controllers/' . ucfirst($controller_name) . 'Controller.php';
		require $file;
		
		$class = ucfirst($controller_name) . 'Controller';
		$controller = new $class();
		
		$this->controller = $controller;
		$this->controller_name = $controller_name;
		return $controller;
	}
	
	public function load_view($name, $variables = [])
	{
		// views belong to the application and are per controller
		$file = $this->path . '/apps/' . $this->app_name . '/views/' . $this->controller_name . '/' . $name . '.php';
		if (file_existS($file))
			return $this->run_php_file($file, $variables);
		
		throw new Exception('View "' . $name . '" not found');
	}
	
	public function load_template($name, $variables)
	{
		// templates belong to the application, but are not per controller
		$file = $this->path . '/apps/' . $this->app_name . '/templates//' . $name . '.php';
		if (file_existS($file))
			return $this->run_php_file($file, $variables);
		
		throw new Exception('Template "' . $name . '" not found');
	}
	
	
	protected function run_php_file($filepath, $variables)
	{
		ob_start();
		extract($variables);
		include $filepath;
		$content = ob_get_clean();
		
		return $content;
	}
	
	public function create_url($route = '/', $args = [], $absolute = false)
	{
		$root_based = (substr($route, 0, 1) == '/');
		$route = trim($route, '/');
		$parts = explode('/', $route);
		
		if ($root_based || count($parts) >= 3)
		{
			$extra_path = implode('/', $parts);
		}
		else if (count($parts) == 0)
		{
			$extra_path = ($this->app_name == $this->config['default_application']) ? '' : ($this->app_name . '/');
			$extra_path .= $this->controller_name . '/' . $this->action;
		}
		else if (count($parts) == 1)
		{
			// defaults to action in current controller in current application
			$extra_path = ($this->app_name == $this->config['default_application']) ? '' : ($this->app_name . '/');
			$extra_path .= $this->controller_name . '/' . $parts[0];
		}
		else if (count($parts) == 2)
		{
			// defaults to controller/action in current appliction
			$extra_path = ($this->app_name == $this->config['default_application']) ? '' : ($this->app_name . '/');
			$extra_path .= $parts[0] . '/' . $parts[1];
		}
		
		// if only one is given, it is an action.
		// if two, is controller+action
		// if starts with '/', this is not relative to current application.
		// if application is the default one, do not include it in the route.
		
		$url = $this->config['base_url'];
		if (!$this->config['hide_index_php_from_url'])
			$url .= '/index.php';
		if (!empty($extra_path))
			$url .= '/' . $extra_path;
		if (!empty($args))
			$url .= '?' . http_build_query($args);
		
		return $url;
	}
	
	public function redirect($route, $params = [])
	{
		if (substr($route, 0, 7) == 'http://' || substr($route, 0, 8) == 'https://')
			$this->redirect_url = $route;
		else
			$this->redirect_url = $this->create_url($route, $params);
	}
}


