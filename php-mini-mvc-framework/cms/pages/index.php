<?php
	$page->sidebar = '<img src="assets/images/home200.jpg" alt="" />';
?>


<h2>The Ever Evolving CMS!</h2>

<p><a href="<?php echo CMS_DIR; ?>/docs">Documentation</a>

<p><?php echo Html::link('Στόχοι', array('features')); ?>

<p>Δοκιμη <?php echo Html::link('Λίστα εγγραφών', array('customers')); ?>, και <?php echo Html::link('Μία εγγραφή', array('customers/3')); ?>

<p><?php echo Html::link('Scaffolding', array('scaffold')); ?>

