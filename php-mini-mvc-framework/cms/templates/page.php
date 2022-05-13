<?php ob_start(); ?>


	<?php if (isset($page->sidebar)):  ?>
	
		<div id="sidebar">
			<?php echo $page->sidebar; ?>
		</div>
		<div id="content-with-sidebar">
		
	<?php endif; ?>

	<?php echo $page->text; ?>
	
	
	<?php if (isset($page->sidebar)):  ?>
	
		</div><!-- content-with-sidebar -->
		<div style="clear:both;"></div>
		
	<?php endif; ?>


<?php app()->load_template(app()->config['default_template'], array('content'=>ob_get_clean())); ?>