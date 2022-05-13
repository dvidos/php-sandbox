<?php

if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['comment']))
{
	$form_name = htmlspecialchars($_POST['name']);
	$form_email = htmlspecialchars($_POST['email']);
	$form_comment = htmlspecialchars($_POST['comment']);
	$form_phone = htmlspecialchars(@$_POST['phone']);
	$form_job = htmlspecialchars(@$_POST['job']);
	
	$to = 'info@x-color.gr';
	$subject = 'Contact Form Message';
	
	$body = 
		"Εχετε μήνυμα από την φόρμα επικοινωνίας στο website σας.<br>" .
		"<br>".
		"Ονομα: $form_name<br>".
		"Email: $form_email<br>".
		"Τηλέφωνο: $form_phone<br>".
		"Επάγγελμα: $form_job<br>".
		"<br>".
		nl2br($form_comment) . "<br>";
	
	$success = app()->send_mail($to, $subject, $body);
	
	if (app()->locale == 'en_gb') 
		$message = $success ? 'Your message was sent successfuly.' : 'An error occured when sending your message.';
	else
		$message = $success ? 'Το μήνυμά σας απεστάλη επιτυχώς.' : 'Παρουσιάστηκε ένα σφάλμα κατά την αποστολή του μηνύματός σας';
		
	app()->load_template('message_box', array('message'=>$message)); 
}		

