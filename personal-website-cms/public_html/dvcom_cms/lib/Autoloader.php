<?php


class Autoloader
{
	public static $root_path = '';
	
	public static $app_name = '';
	
	public static function register()
	{
		spl_autoload_register([self::class, 'try_to_load']);
	}
	
	public static function try_to_load($class)
	{
		// first current application
		if (!empty(self::$app_name))
		{
			$app_folders = [ 'models', 'controllers', 'lib' ];
			foreach ($app_folders as $folder)
			{
				$file = self::$root_path . '/' . self::$app_name . '/' . $folder . '/' . $class . '.php';
				if (file_exists($file))
				{
					include $file;
					return;
				}
			}
		}
		
		// then try cms library
		$cms_folders = [ 'models', 'lib' ];
		foreach ($cms_folders as $folder)
		{
			$file = self::$root_path . '/' . $folder . '/' . $class . '.php';
			if (file_exists($file))
			{
				include $file;
				return;
			}
		}
	}
}
