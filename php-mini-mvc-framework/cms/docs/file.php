<?php
	$title = 'File';
	$description = <<<'EOT'
		Είναι ένα αντικείμενο που περιγράφει ένα αρχείο. Χρησιμοποιείται για να φτιάχνουμε image galleries ή λίστες με αρχεία προς κατέβασμα.
EOT;

	$properties = array(
		'path'=>'Το σχετικό path για links κλπ (πχ "files/images/photos/photo1.jpg")',
		'filename'=>'Το όνομα αρχείου με επέκταση (πχ "photo1.jpg")',
		'name'=>'Το όνομα αρχείου χωρίς επέκταση (πχ "photo1")',
		'extension'=>'Η επέκταση χωρίς τελεία (πχ "jpg")',
		'size'=>'Το μέγεθος σε bytes',
		'ctime'=>'Το created timestamp του αρχείου',
		'mtime'=>'Το modified timestamp του αρχείου',
	);
