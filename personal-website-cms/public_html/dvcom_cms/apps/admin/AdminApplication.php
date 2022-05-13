<?php

class AdminApplication extends Application
{
	public $default_controller = 'articles';
	
	public function beforeAction()
	{
		if (empty($_SESSION['logged_in']) || empty($_SESSION['user']))
		{
			cms()->redirect('/site/signIn', ['redirect' => '/admin']);
			return false;
		}
	}
}

