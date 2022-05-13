<?php
	$title = 'Db';
	$description = <<<'EOT'
		Είναι ένα αντικείμενο για την βάση δεδομένων. 
		
		Υπάρχει ως ένα instance στο app()->db. Στο config δηλώνουμε τιμές αρχικοποίησής του
EOT;

	$properties = array(
		'find($table, $criteria)'=>
		'Φέρνει μία εγγραφή (associative array)',
		
		'findAll($table, $criteria)'=>
		'Φέρνει πολλές εγγραφές (array of assocs)',
		
		'insert($table, $values)'=>
		'Εισάγει νέα εγγραφή',
		
		'update($table, $values, $criteria)'=>
		'Ενημερώνει εγγραφές',
		
		'delete($table, $criteria)'=>
		'Διαγράφει εγγραφές',
		
		'count($table, $criteria)'=>
		'Μετράει εγγραφές',
		
		'avg($table, $field, $criteria)'=>
		'Υπολογίζει μέσο όρο',
		
		'min($table, $field, $criteria)'=>
		'Υπολογίζει ελάχιστη τιμή',
		
		'max($table, $field, $criteria)'=>
		'Υπολογίζει μέγιστη τιμή',
		
		'sum($table, $field, $criteria)'=>
		'Υπολογίζει άθροισμα',
		
		'$criteria'=>"Associative πίνακας που μπορεί να περιλαμβάνει τα προεραιτικά κλειδιά:\n" .
			"- condition (συνθήκη),\n".
			"- params (παράμετροι στο condition)\n".
			"- order (είτε string, είτε array)\n".
			"- fields (ποιά πεδία να επιστραφούν)\n".
			"- distinct (αν θέλουμε μοναδικές τιμές)\n".
			"- groupBy (αν θέλουμε ομαδοποίηση)\n".
			"- having (συνθήκη ομαδοποιημένων)\n".
			"- limit (πόσες εγγραφές να επιστραφούν)\n".
			"- offset (ποιά θα επιστραφεί ως πρώτη)\n".
			"Αν η τιμή είναι string αντί για πίνακας, θεωρείται συνθήκη."
	);
