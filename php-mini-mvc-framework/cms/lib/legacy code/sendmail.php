<?php
	/* set an HTML form to use this script, as follows:
	
	<form method="POST" action="include/sendmail.php">
		<input type="hidden" name="redirect" value="thankyou.php" />
		<p>
			<label for="name">Ονομα</label><br />
			<input type="text" name="name" value="" />
		</p>
		<p>
			<label for="name">Email</label><br />
			<input type="text" name="email" value="" />
		</p>
		<p>
			<label for="name">Τηλέφωνο</label><br />
			<input type="text" name="phone" value="" />
		</p>
		<p>
			<label for="name">Επάγγελμα</label><br />
			<input type="text" name="job" value="" />
		</p>
		<p>
			<label for="comment">Κείμενο</label><br />
			<textarea name="comment"></textarea>
		</p>
		<p>
			<input type="submit" value="Αποστολή" />
		</p>
	</form>
	*/

	$email_from = "Website <info@x-color.gr>";
    $email_to = "info@x-color.gr";
    $email_subject = "Contact Form Message";
	
    // validation expected data exists
    if(!isset($_POST['name']) ||
        !isset($_POST['email']) ||
        !isset($_POST['comment']) ||
        !isset($_POST['redirect'])) {
        die('We are sorry, but there appears to be a problem with the form you submitted.');      
    }
    
    $user_name = $_POST['name'];
    $user_email = $_POST['email'];
    $user_comment = $_POST['comment'];
	$user_phone = @$_POST['phone'];
	$user_job = @$_POST['job'];
	$redirect = $_POST['redirect'];
	
	
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      $string = str_replace($bad,"",$string);
	  $string = str_replace("<", "%lt;", $string);
	  $string = str_replace(">", "%gt;", $string);
	  
	  return $string;
    }
     
	$headers = "MIME-Version: 1.0\r\n";
	$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
	$headers .= 'From: '.$email_from."\r\n";
	$headers .= 'Reply-To: '.$email_from."\r\n";
	$headers .= 'X-Mailer: PHP/' . phpversion();
	
    $email_message = "Κάποιος χρήστης έγραψε ένα μήνυμα στην φόρμα επικοινωνίας στο website σας.\n";
	$email_message .= "Τα δεδομένα που εισήγαγε είναι:\n\n";
    $email_message .= "Ονομα: ".clean_string($user_name)."\n";
    $email_message .= "Email: ".clean_string($user_email)."\n";
    $email_message .= "Τηλέφωνο: ".clean_string($user_phone)."\n";
    $email_message .= "Επάγγελμα: ".clean_string($user_job)."\n";
    $email_message .= "Κείμενο:\n";
	$email_message .= "-----------------------------------------------\n";
	$email_message .= clean_string($user_comment)."\n";
	$email_message .= "-----------------------------------------------\n";
	$email_message .= "Τέλος μηνύματος\n";
	
    
	if (!mail($email_to, $email_subject, $email_message, $headers))
		die('<html><body>Error sending email!<pre>' . $headers . '<br /><br />' . $email_message . '</pre></body></html>');
	
	header("Location: " . $redirect);
?>