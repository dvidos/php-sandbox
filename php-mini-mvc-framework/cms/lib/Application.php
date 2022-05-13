<?php

/**
 * Application class. Can be accessed by the app() function.
 */
 
 
class Application
{
	// the path (route) requested
	var $path;
	
	// the current locale
	var $locale;
	
	// the cofiguration file
	var $config;
	
	// associative with routes and their callables (controllers)
	var $routes;

	protected function __construct()
	{
	}
	
	// returns a singleton instance. normally called from a simple function called app()
	static $instance = null;
	static function getInstance()
	{
		if (self::$instance == null)
		{
			self::$instance = new Application();
			self::$instance->initialize();
		}
	
		return self::$instance;
	}
	
	
	
	/**
	 * Runtime: init(), run(), show_page()
	 */
	
	function initialize()
	{
		// load configuration.
		$this->config = require(CMS_DIR . '/config/config.php');
		$this->log_debug('Application config: ' . var_export($this->config, true));
		
		// now we have log level, log that we are running
		$this->log_verbose('init()');
		
		// check if debug information should be shown
		$this->log_info('$config[debug] is ' . ($this->config['debug'] ? 'ON' : 'off'));
		if ($this->config['debug'])
		{
			error_reporting(-1);
			ini_set('display_errors', 1);
			ini_set('log_errors', 1);
			ini_set('error_log', CMS_DIR .'/logs/errors.log');
		}
		else
		{
			error_reporting(0);
			ini_set('display_errors', 0);
			ini_set('log_errors', 1);
			ini_set('error_log', CMS_DIR .'/logs/errors.log');
		}
		
		// find default or requested locale
		$this->locale = array_key_exists('locale', $_REQUEST) ? $_REQUEST['locale'] : $this->config['default_locale'];
		
		// parse requested path
		if (array_key_exists('PATH_INFO', $_SERVER))
			$this->path = $_SERVER['PATH_INFO'];
		else if (array_key_exists('p', $_REQUEST))
			$this->path = $_REQUEST['p'];
		else
			$this->path = '/';
			
		$this->path = str_replace('../', '', $this->path);
		$this->path = str_replace('..\\', '', $this->path);
		$this->path = '/' . trim($this->path, '/');

		// this irrelevant if we have a database or not...
		$this->db = new Db();
		
		// prepare for routing selection
		$this->routes = array();
	}

	
	function base_url()
	{
		// return the base url to create relative urls.
		// for example, if localhost/index.php, should return '', if localhost/test/index.php should return '/test'
		// always append a '/' at the base_url
		
	}
	
	function route($route, $callable)
	{
		// route must start with a single forward slash (eg, "/about", or "/", or "/customers/{\d+}"
		$route = '/' . strtolower(ltrim($route, '/'));
		
		$this->routes[$route] = $callable;
	}
	
	function run()
	{
		$this->log_verbose('run(), path=' . $this->path);
		
		// try to match a route.
		if (!$this->find_and_run_route($this->path))
			$this->load_template('404');
	}

	function find_and_run_route($path)
	{
		$this->log_verbose('find_and_run_route("' . $path . '")');
		
		// should match something like "/customers/{id}" into customersHandler($id)
		foreach ($this->routes as $route => $callable)
		{
			$variables = array();
			if ($this->parse_route($route, $path, $variables))
			{
				$this->log_verbose('calling route "' . $route . '", with ' . count($variables) . ' variables');
				call_user_func_array($callable, $variables);
				return true;
			}
		}
		
		// no route found. now what ?
		return false;
	}
	
	function parse_route($route, $path, &$variables)
	{
		$this->log_debug('trying to parse route: "' . $route . '"');
		$variables = array();
		$pos = strpos($route, '{');
		
		// no variables, match the whole pattern.
		if ($pos === false)
			return ($route == $path);

		// some variables, rule out a possible no, by checking the first part
		if ($pos > 1 && strtolower(substr($route, 0, $pos)) != strtolower(substr($path, 0, $pos)))
			return false;
		
		// now, we need to parse into regexps?
		// yes, the route IS the pattern, only "{}" are used instead of "()"
		
		$pattern = '#^' . str_replace(array('{', '}'), array('(', ')'), $route) . '$#i';
		$this->log_debug('preg pattern is: "' . $pattern . '"');
		$match = preg_match($pattern, $path, $variables);
		if ($match === false || $match === 0)
			return false;
		
		array_shift($variables);
		$this->log_debug('pattern matched with variables: ' . var_export($variables, true));
		return true;
	}
	
	function handle_page($page_name, $page_template = 'page')
	{
		$this->log_verbose('handle_page("' . $page_name . '")');
		if (!$this->page_exists($page_name))
		{
			$this->load_template('404');
			return;
		}
		
		$page = $this->load_page($page_name);
		$template = $page_template;
		if (!empty($page->template) && $this->template_exists($page->template))
			$template = $page->template;
			
		$this->load_template($template, array('page'=>$page));
	}
	
	
	
	
	

	/**
	 * Page manipulation
	 */
	
	function page_exists($rel_path)
	{
		$filename = $this->find_possible_locale_php_file('pages', $rel_path);
		return ($filename !== false);
	}

	function load_page($rel_path)
	{
		$this->log_verbose('load_page("' . $rel_path . '")');
		$filename = $this->find_possible_locale_php_file('pages', $rel_path);
		if ($filename === false)
		{
			$this->log_error('Page "' . $rel_path . '" not found');
			return null;
		}
		
		$page = new Page();
		
		ob_start();
		require($filename);
		$page->text = ob_get_clean();
		
		$page->path = $rel_path;
		$page->mtime = filemtime($filename);
		return $page;
	}

	function load_pages($rel_folder_path, $order_by = 'filename', $order_asc = true)
	{
		$this->log_verbose('load_pages("' . $rel_folder_path . '", "' . $order_by . '", ' . ($order_asc ? 'true' : 'false') . ')');
		// $order_by can be by filename, or file timestamp
		// $order_asc can be false for descending order
		
		$folder = CMS_DIR . '/pages/' . $rel_folder_path;
		$this->log_debug('reading ' . $folder);
		$files_arr = array();
		if ($h = opendir($folder))
		{
			while (($f = readdir($h)) != null)
			{
				if (!is_file($folder . '/' . $f))
					continue;
				
				$this->log_debug('$f = ' . $f);
				$files_arr[] = array(
					'f'=> $f,
					'ct'=> filectime($folder . '/' . $f),
					'mt'=> filemtime($folder . '/' . $f),
					'p'=> $rel_folder_path . '/' . pathinfo($f, PATHINFO_FILENAME), // requires PHP 5.2.
				);
			}
		}
		closedir($h);
		
		// asort($files_arr);
		$func = 'load_pages_sort_' . $order_by . '_' . ($order_asc ? 'asc' : 'desc');
		if (!method_exists($this, $func))
			$this->log_error('load_pages(), sorting function "' . $func . '" does not exist');
		usort($files_arr, array($this, $func));
		
		$pages = array();
		foreach ($files_arr as $file)
			$pages[] = $this->load_page($file['p']);
		
		return $pages;
	}
	function load_pages_sort_filename_asc($a, $b) { return strcmp($a['f'], $b['f']); }
	function load_pages_sort_filename_desc($a, $b) { return strcmp($a['f'], $b['f']) * -1; }
	function load_pages_sort_mtime_asc($a, $b) { return $a['mt'] - $b['mt']; }
	function load_pages_sort_mtime_desc($a, $b) { return $b['mt'] - $a['mt']; }
	function load_pages_sort_ctime_asc($a, $b) { return $a['ct'] - $b['ct']; }
	function load_pages_sort_ctime_desc($a, $b) { return $b['ct'] - $a['ct']; }
	
	
	
	
	
	/**
	 * Templates manipulation
	 */
	function template_exists($template_name = 'default')
	{
		$filename = $this->find_possible_locale_php_file('templates', $template_name);
		return ($filename !== false);
	}

	function load_template($template_name = 'default', $variables = array(), $prefer_return = false)
	{
		$this->log_verbose('load_template("' . $template_name . '", $variables)');
		$filename = $this->find_possible_locale_php_file('templates', $template_name);
		if ($filename === false)
		{
			$this->log_error('Template "' . $template_name . '" not found');
			return '';
		}
		
		extract($variables);
		ob_start();
		include($filename);
		$result = ob_get_clean();
		
		if ($prefer_return)
			return $result;
		else
			echo $result;
	}
	
	function find_possible_locale_php_file($folder, $name)
	{
		// if different locale, try searching in subfolder
		if ($this->locale != $this->config['source_locale'])
		{
			$path = str_replace('//', '/', CMS_DIR . '/' . $folder . '/' . $this->locale . '/' . $name . '.php');
			if (file_exists($path))
				return $path;
		}
		
		// if no locale, or failed to spot it, load default
		$path = str_replace('//', '/', CMS_DIR . '/' . $folder . '/' . $name . '.php');
		if (file_exists($path))
			return $path;
			
		// nope, failed...
		return false;
	}
	
	private $templates_stack = array();
	
	/**
	 * Start embedding content in specific template
	 */
	public function begin_embedding($target_template = null)
	{
		if ($target_template == null)
			$target_template = $this->config['default_template'];
		
		self::log_debug('begin_embedding(\'' . $target_template . '\');');
		$this->templates_stack[] = $target_template;
		ob_start();
	}
	
	
	/**
	 * Stop capturing output and embed captured output to the template
	 * declared on begin_embedding();
	 */
	public function end_embedding()
	{
		if (count($this->templates_stack) < 1)
			throw new Exception("stop_embedding() without start_embedding() called");
		
		$target_template = array_pop($this->templates_stack);
		$content = ob_get_clean();
		self::log_debug('end_embedding(\'' . $target_template . '\'), captured content ' . mb_strlen($content) . ' characters');
		
		$this->load_template($target_template, array(
			'content'=>$content
		));
	}
	
	
	
	



	/** 
	 * Files manipulation
	 */
	function find_files($rel_folder_path, $order_by = 'filename', $order_asc = true)
	{
		$this->log_verbose('find_files("' . $rel_folder_path . '", "' . $order_by . '", ' . ($order_asc ? 'true' : 'false') . ')');
		// $order_by can be by filename, or file timestamp
		// $order_asc can be false for descending order
		
		$folder = $rel_folder_path;
		$this->log_debug('reading ' . $folder);
		$entries = array();
		if (!$h = opendir($folder))
			return array();
		
		while (($f = readdir($h)) != null)
		{
			if (!is_file($folder . '/' . $f))
				continue;
			
			$this->log_debug('$f = ' . $f);
			$entries[] = $folder . '/' . $f;
		}
		closedir($h);
		
		$file_objs = array();
		foreach ($entries as $entry)
		{
			$file_obj = new File();
			$file_obj->path = $entry;
			$file_obj->filename = pathinfo($entry, PATHINFO_BASENAME);
			$file_obj->name = pathinfo($entry, PATHINFO_FILENAME);
			$file_obj->extension = pathinfo($entry, PATHINFO_EXTENSION);
			$file_obj->size = filesize($entry);
			$file_obj->ctime = filectime($entry);
			$file_obj->mtime = filemtime($entry);
			$file_objs[] = $file_obj;
		}

		$func = 'find_files_sort_' . $order_by . '_' . ($order_asc ? 'asc' : 'desc');
		if (!method_exists($this, $func))
			$this->log_error('find_files(), sorting function "' . $func . '" does not exist');
		usort($file_objs, array($this, $func));
		
		return $file_objs;
	}
	function find_files_sort_filename_asc($a, $b) { return strcmp($a->path, $b->path); }
	function find_files_sort_filename_desc($a, $b) { return strcmp($b->path, $a->path); }
	function find_files_sort_mtime_asc($a, $b) { return $a->mtime - $b->mtime; }
	function find_files_sort_mtime_desc($a, $b) { return $b->mtime - $a->mtime; }
	function find_files_sort_ctime_asc($a, $b) { return $a->ctime - $b->ctime; }
	function find_files_sort_ctime_desc($a, $b) { return $b->ctime - $a->ctime; }
	function find_files_sort_size_asc($a, $b) { return $a->size - $b->size; }
	function find_files_sort_size_desc($a, $b) { return $b->size - $a->size; }

	



	/**
	 * Logging functions
	 */
	 
	function log_error($msg)   { $this->log_at_level(LOG_LEVEL_ERROR,   'error', $msg); }
	function log_warning($msg) { $this->log_at_level(LOG_LEVEL_WARNING, 'warning', $msg); }
	function log_info($msg)    { $this->log_at_level(LOG_LEVEL_INFO,    'info', $msg); }
	function log_debug($msg)   { $this->log_at_level(LOG_LEVEL_DEBUG,   'debug', $msg); }
	function log_verbose($msg)   { $this->log_at_level(LOG_LEVEL_VERBOSE,   'verbose', $msg); }
	function log_at_level($level, $prefix, $msg)
	{
		if ($level < $this->config['log_level'])
			return;
		
		$fname = CMS_DIR . '/logs/log-' . date('Y-m-d') . '.txt';
		$h = fopen($fname, 'a');
		if (!$h)
			return;
		
		$prefix = date('Y/m/d H:i:s') . ' [' . $prefix . '] ';
		if (is_array($msg))
		{
			foreach ($msg as $line)
			{
				fwrite($h, $prefix);
				fwrite($h, $line);
				fwrite($h, "\r\n");
			}
		}
		else
		{
			fwrite($h, $prefix);
			fwrite($h, $msg);
			fwrite($h, "\r\n");
		}
		
		fclose($h);
	}
	 
	 
	 
	/**
	 * Create Url
	 */
	function create_url($url = array())
	{
		if (empty($url)) {
			return 'index.php';
		}
		else if (is_array($url))
		{
			$href = 'index.php';
			$separator = '?';

			// first item in array is the path
			$path = array_shift($url);
			if (!empty($path)) {
				$href .= $separator . 'p=' . rawurlencode($path);
				$separator = '&';
			}
			
			// we add the locale if not default
			if ($this->locale != $this->config['default_locale'] && !array_key_exists('locale', $url)) {
				$href .= $separator . 'locale=' . rawurlencode($this->locale);
				$separator = '&';
			}
			
			// the rest, as variables
			foreach ($url as $name => $value)
			{
				if ($name == '#') {
					// we append named anchor
					$href .= '#' . rawurlencode($value);
				} else {
					$href .= $separator . rawurlencode($name) . '=' . rawurlencode($value);
					$separator = '&';
				}
			}
			
			return $href;
		}
		else {
			return $url;
		}
	}
	
	
	
	
	/** 
	 * Send mail (in utf-8)
	 */
	public function send_mail($to, $subject, $body)
	{
		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=UTF-8\r\n";
		$headers .= 'From: ' . $this->config['mail_sender'] . "\r\n";
		$headers .= 'Reply-To: ' . $this->config['mail_sender'] . "\r\n";
		$headers .= 'X-Mailer: PHP/' . phpversion();
		
		return mail($to, $subject, $body, $headers);
	}
}


