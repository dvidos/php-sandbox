<?php


class Dispatcher
{
	protected $cms = null;
	
	public function __construct(Cms $cms)
	{
		$this->cms = $cms;
	}
	
	public function dispatch()
	{
		// echo '<pre>' . var_export($_SERVER, true) . '</pre>';
		
		// index.php				-> non existing key
		// index.php/				-> /
		// index.php/abc			-> /abc
		// index.php/abc/			-> /abc/
		// index.php/abc/def		-> /abc/def
		
		$route = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO'], '/') : '';
		
		// map a different route
		foreach ($this->cms->config['routes_mapping'] as $source => $target)
		{
			// verbatim match
			if ($route == $source) {
				if (is_string($target))	{
					$route = $target;
				} else if (is_array($target)) {
					$route = array_shift($target);
					foreach ($target as $key => $value) {
						$_REQUEST[$key] = $value;
					}
				}
				break;
			}
		}
		
		$parts = empty($route) ? [] : explode('/', $route);
		
		// discover application
		if (empty($parts))
		{
			$app_name = $this->cms->config['default_application'];
		}
		else
		{
			if ($this->cms->application_exists($parts[0]))
				$app_name = array_shift($parts);
			else
				$app_name = $this->cms->config['default_application'];
		}
		
		// load application
		if (!$this->cms->application_exists($app_name))
			throw new HttpException(404, 'Application "' . $app_name . '" not found');

		$this->cms->load_application($app_name);
		
		
		// discover controller
		if (empty($parts))
			$controller_name = $this->cms->app->default_controller ?: $this->cms->config['default_controller'];
		else
			$controller_name = array_shift($parts);
		
		if (!$this->cms->controller_exists($app_name, $controller_name))
			throw new HttpException(404, 'Controller "' . $controller_name . '" not found in application "' . $app_name . '"');
		
		$this->cms->load_controller($app_name, $controller_name);
		
		
		if (empty($parts))
			$action_name = $this->cms->controller->default_action ?: ($this->cms->app->default_action ?: $this->cms->config['default_action']);
		else
			$action_name = array_shift($parts);
		$this->cms->action_name = $action_name;

		// discover action
		$method_name = 'action' . ucfirst($action_name);
		if (!method_exists($this->cms->controller, $method_name))
			throw new HttpException(404, 'Action "' . $action_name . '" not found in controller "' . $controller_name . '" of application "' . $app_name . '"');
		
		// pass any http arguments with the same name
		$call_args = [];
		$method = new ReflectionMethod(get_class($this->cms->controller), $method_name);
		$params = $method->getParameters();
		foreach ($params as $param)
		{
			if (isset($_REQUEST[$param->getName()]))
				$call_args[] = $_REQUEST[$param->getName()];
			else
				$call_args[] = $param->isOptional() ? $param->getDefaultValue() : null;
		}
		
		// since we loaded the application, set autoloader to prefer application's models, controllers etc.
		Autoloader::$app_name = $app_name;
		
		
		// start capturing output
		ob_start();
		
		// make sure we call the appropriate before/after functions
		// don't throw exceptions to allow redirections to function
		if ($this->cms->app->beforeAction() !== false)
		{
			if ($this->cms->controller->beforeAction() !== false)
			{
				// will call this method
				call_user_func_array([$this->cms->controller, $method_name], $call_args);
				
				$this->cms->controller->afterAction();
				
				$this->cms->app->afterAction();
			}
		}
		
		if (!empty($this->cms->redirect_url))
		{
			http_response_code(302);
			header('Location: ' . $this->cms->redirect_url);
		}
		else
		{
			// could have a response object and set content-type to json etc. for now, default to html
			
			// find template
			$content = ob_get_clean();
			$template_name = $this->cms->controller->template ?: ($this->cms->app->template ?: $this->cms->config['default_template']);
			echo $this->cms->load_template($template_name, ['content' => $content]);
		}
		
	}
	
	public function createRoute($app_name, $controller_name, $action_name)
	{
		
	}
}
