<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" >
<head>
	<title><?php 
		if (isset($page->title) && $page->title != '') 
			echo $page->title . ' - '; 
		echo app()->config['site_title']; 
	?></title>
	<meta charset="utf-8">
	<meta name="description" content="<?php echo ($page->meta_description != '') ? $page->meta_description : app()->config['default_meta_description']; ?>" />
	<meta name="keywords" content="<?php echo ($page->meta_keywords != '') ? $page->meta_keywords : app()->config['default_meta_keywords']; ?>" />
	<link rel="icon" href="<?php echo app()->config['favicon_rel_path']; ?>" />

	<script type="text/javascript" src="assets/js/jquery-1.7.2.min.js"></script>
	<script type="text/javascript" src="assets/js/lightbox.js"></script>
	<script type="text/javascript" src="assets/js/slideshow.js"></script>
	<link rel="stylesheet" type="text/css" href="assets/css/lightbox.css" />
	<link rel="stylesheet" type="text/css" href="assets/css/style.css" />
</head>
<body>
<div id="header" style="height: auto; padding: .5em .5em; background-color: #ddd; border-bottom: 1px solid #ccc;"><div class="page-column">
	<div style="float:left;">
		<a href="index.php">HOME</a>
	</div>
	<div style="float:right;">
		<a href="<?php echo app()->create_url(array('', 'locale'=>'el_gr')); ?>"><img src="assets/images/template/lang_greek.png" style="border:0;" alt="GR" title="Ελληνικά"></a> 
		<a href="<?php echo app()->create_url(array('', 'locale'=>'en_gb')); ?>"><img src="assets/images/template/lang_english.png" style="border:0;" alt="EN" title="English"></a>
	</div>
	<div style="clear:both;"></div>
</div></div>
<div id="content" style="margin-top: 1em;"><div class="page-column">


	<?php echo $content; ?>


</div></div><!-- page-column, layout-content -->
</body>
</html>
