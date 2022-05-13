<?php

return [
	'site_title' => 'Dimitris Vidos (dev)',
	
	'db' => [
		// sqlite dsn: 'sqlite:' . CMS_DIR . '/data/essential sql.db', use null for username and password
		'dsn' => 'mysql:host=localhost;dbname=dvcom_db1',
		'user' => 'root',
		'pass' => '',
		'tables_prefix' => 'cms_',
	],
	
	// in my dev box, the site runs in subfolder
	'base_url' => '/dvcom',
];

