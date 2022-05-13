<?php

class Controller
{
	// the default action. override in child classes
	public $default_action = '';

	// the output template. override in child classes, or set within an action
	public $template = '';

	
	
	public function beforeAction()
	{
		// return false in child classes to stop execution
	}
	
	public function afterAction()
	{
	}
}

