<?php
	$page->title = 'Επικοινωνία';
	$page->sidebar = 
		'<h2>Παπασταθόπουλος Δημήτρης</h2>' . 
		'<p>Αργους 87,<br />21100 Ναύπλιο,<br />Αργολίδας</p>'.
		'<p>Τηλ &amp; Φαξ: 27520-22805<br />email: <a href="mailto:info@x-color.gr">info@x-color.gr</a></p>'.
		'<a target="_blank" href="https://www.google.com/maps/place/Argous+87,+Nafplio+211+00,+Greece/@37.576818,22.806505,15z/data=!4m2!3m1!1s0x149ffa83be9f3413:0x4b1de1cbcc31b952?hl=en-US"><img alt="MAP" src="assets/images/map.png" style="border: 1px solid #aaa;" ></a>';
?>

<h1>Επικοινωνία</h1>

<?php 
	app()->load_template('handle_mail_form');
?>

<form method="POST">
	<p>
		<label for="name">Ονομα</label><br />
		<input type="text" name="name" value="" />
	</p>
	<p>
		<label for="email">Email</label><br />
		<input type="text" name="email" value="" />
	</p>
	<p>
		<label for="phone">Τηλέφωνο</label><br />
		<input type="text" name="phone" value="" />
	</p>
	<p>
		<label for="job">Επάγγελμα</label><br />
		<input type="text" name="job" value="" />
	</p>
	<p>
		<label for="comment">Κείμενο</label><br />
		<textarea name="comment" rows="5"></textarea>
	</p>
	<p>
		<input type="submit" value="Αποστολή" />
	</p>
</form>


