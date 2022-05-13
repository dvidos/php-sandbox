<?php

define('LOG_LEVEL_ERROR', 5);
define('LOG_LEVEL_WARNING', 4);
define('LOG_LEVEL_INFO', 3);
define('LOG_LEVEL_DEBUG', 2);
define('LOG_LEVEL_VERBOSE', 1);
define('LOG_LEVEL_NONE', 0);


return array(

	// debug behaviour
	'debug' => true,
	
	// log level for logs: 0=means none
	'log_level' => LOG_LEVEL_DEBUG,
	
	'site_title'=>'DV CMS',
	'favicon_rel_path' => 'assets/images/favicon/four colors.ico',
	
	'default_meta_description'=>'Δίνουμε εδώ μια γενική περιγραφή του site, θα χρησιμοποιείται σε κάθε σελίδα.',
	'default_meta_keywords'=>'βερνίκια, βαφές, χρώματα, χρωστικά, ξύλου, γυαλιού, τεχνοτροπίες, εφέ, εφφέ',
	
	// name of default template to load. located inside cms/templates.
	'default_template' => 'main',
	
	// can be overriden by GET variable and inside a page
	'default_locale' => 'el_gr',
	'source_locale' => 'el_gr',
	
	// the sender of email in application->send_mail()
	'mail_sender' => 'Website <info@example.gr>',
	
	// database access params
	'db_pdo_dsn'=>'sqlite:' . CMS_DIR . '/data/essential sql.db',
	'db_pdo_user'=>null,
	'db_pdo_pass'=>null,
	
);