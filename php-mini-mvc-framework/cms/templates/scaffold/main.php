<?php app()->begin_embedding('main'); ?>
<style>
	.flash-info { background-color: #cfd; border: 1px solid #060; padding: .5em 1em; }
	.scaffold-entries {
		border-collapse: collapse; 
		width: 100%; 
		font-size: 90%; 
	}
	.scaffold-entries tr { }
	.scaffold-entries th { 
		text-align: left; 
	}
	.scaffold-entries td { 
		border: 1px solid #ddd; 
		padding: .25em .5em; 
	}
</style>
<p class="flash-info">Note: Scaffold is under construction</p>



<?php echo $content; ?>



<?php 
	if (isset($tables))
		echo '<p><pre><b>$tables</b> = '.var_export($tables, true).'</pre></p>';
	if (isset($tableInfo))
		echo '<p><pre><b>$tableInfo</b> = '.var_export($tableInfo, true).'</pre></p>';
	if (isset($recs))
		echo '<p><pre><b>$recs</b> = '.var_export($recs, true).'</pre></p>';
	if (isset($record))
		echo '<p><pre><b>$record</b> = '.var_export($record, true).'</pre></p>';
?>
<?php app()->end_embedding(); ?>
