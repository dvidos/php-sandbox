<?php
	$title = 'Configuration';
	$description = <<<'EOT'
		Στο lib/config.php υπάρχει ένας πίνακας που θα φορτωθεί στο app()->config. Αυτός μπορεί να περιέχει:
EOT;

	$properties = array(
		'debug'=>
		'true/false, αν θα εμφανίζονται μηνύματα σφαλμάτων στην σελίδα',
		
		'log_level'=>
		'Το επίπεδο σοβαρότητας για τα logs. Μπορεί να είναι: LOG_LEVEL_ERROR, LOG_LEVEL_WARNING, LOG_LEVEL_INFO, LOG_LEVEL_TRACE, LOG_LEVEL_DEBUG, LOG_LEVEL_NONE',
		
		'site_title'=>
		'Τίτλος του site.',
		
		'favicon_rel_path'=>
		'Το αρχείο εικόνας για favicon.',
		
		'default_meta_description'=>
		'Το default meta description, αν η σελίδα δεν ορίζει δικό της',
		
		'default_meta_keywords'=>
		'Το default meta keywords, αν η σελίδα δεν ορίζει δικά της',
		
		'default_template'=>
		'Το default template που θα χρησιμοποιηθεί, αν η σελίδα δεν ορίζει δικό της',
		
		'default_locale'=>
		'Το default locale που θα χρησιμοποιηθεί, μπορεί να είναι διαφορετικό από αυτό του κώδικα',
		
		'source_locale'=>
		'Το locale που είναι γραμμένος ο κώδικας, λογικά το el_gr',
		
		'mail_sender'=>
		'Ο αποστολέας που χρησιμοποιείται για το application->send_mail(). πχ "Website <info@example.com>"',
		
		'database_file'=>
		'Το αρχείο της sqlite βάσης δεδομένων. Συνήθως βρίσκεται στο cms/data',
	);
