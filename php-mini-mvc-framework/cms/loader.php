<?php



/**
 * main cms included file
 */



// __DIR__ is this file's directory. we therefore find the name of this directory (usually cms)
define('CMS_DIR', basename(__DIR__));

// register autoloaders for loading classes from our directories
spl_autoload_register(function($class) { include(CMS_DIR . '/lib/' . $class . '.php'); });
spl_autoload_register(function($class) { include(CMS_DIR . '/models/' . $class . '.php'); });

// create a global function to return an application instance.
function app() { return Application::getInstance(); }