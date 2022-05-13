<?php

/**
 * Application class. Can be accessed by the app() function.
 */
 
 
class Application
{
	// the default controller. override in child classes
	public $default_controller = '';

	// the default action. override in child classes
	public $default_action = '';

	// the output template. override in child classes
	public $template = '';

	
	
	public function beforeAction()
	{
		// return false in child classes to stop execution
	}
	
	public function afterAction()
	{
	}
}


