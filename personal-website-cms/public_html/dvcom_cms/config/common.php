<?php

return [

	// main site title
	'site_title' => 'Dimitris Vidos',
	// site subtitle
	'site_subtitle' => 'Thoughts and words on software construction',

	// a way to decide which environment we run under
	// see EnvironmentDetector class
	'dev_hosts' => [ 'localhost' ],
	'test_hosts' => [ 'testwww.dimitrisvidos.com' ],
	'prod_hosts' => [ 'dimitrisvidos.com', 'www.dimitrisvidos.com' ],
	
	// default application if only the site was requested
	'default_application' => 'frontend',

	// default controller if none requested. Can override in Application.
	'default_controller' => 'site',
	
	// default action if none requested. can override in Application and in Controller
	'default_action' => 'index',
	
	// default template. can be overriden in Application and in Controller
	'default_template' => 'default',
	
	// other routes
	'routes_mapping' => [
		'about' => ['site/about'],
		'copyright' => ['site/copyright'],
	],
	
	// base url, normally '' for root server, '/dvcom' for my dev box. slash only if not top level
	'base_url' => '',
	
	// should hide index.php from urls? set after hiding it with .htaccess
	'hide_index_php_from_url' => false,

	// whether to show errors or not
	'show_errors' => true,
];

