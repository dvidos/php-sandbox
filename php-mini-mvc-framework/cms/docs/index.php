<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-gb" lang="en-gb" >
<head>
	<title>DV CMS Documentation</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<style>
		body { font-family: Verdana; font-size: 13px; background-color: #e6e7e8; margin: 0; }
		div#main-column { background-color: white; width: 960px; margin: 0 auto; padding: 1em 3em; border-left: 1px solid #777; border-right: 1px solid #777; }
		div.contents-list { float: left; width: 33%; }
		div.contents { float: right; width: 66%; }
		table.properties { width: 100%; margin: 1em 0; padding: 0; border-collapse: collapse; }
		table.properties tr { margin: 0; padding: 0; }
		table.properties td { margin: 0; padding: 2px 4px; border: 1px solid #ddd; vertical-align: top; }
		table.properties td.name { font-family: "Lucida Console", "Courier New", mono; font-size: 12px; white-space: nowrap; }
		a { color: #006CC1; text-decoration: none; }
		h1 { color: #888; border-bottom: 2px solid #aaa; padding-bottom: .25em; }
		h2 { color: #006CC1; margin: .5em 0; text-shadow: 0px 1px 1px rgba(0,0,0,.5); }
		h3 { color: #006CC1; margin: .5em 0; text-shadow: 0px 1px 1px rgba(0,0,0,.5); }
		ul { padding-left: 1.25em; }
		ul ul { padding-left: 2.5em; list-style-type: none; }
		div.section { margin-bottom: 3em; }
		div.object { margin-bottom: 3em; }
		p.property-name { font-weight: bold; font-size: 100%; color: #092; margin: 1em 0 1em 0; background-color: #eee; padding: .5em;}
		p.property-description { margin: 0 0 2em 0; }
		div.contents div.section:first-child h2 { margin-top: 0; }
	</style>
	<?php
		function tag($tag, $class, $content) { return '<' . $tag . ' class="' . $class . '">' . $content . '</' . $tag . '>' . "\r\n"; }
		function a($text, $page) { return '<a href="?page=' . $page . '">' . $text . '</a>'; }
	?>
</head>
<body>
<div id="main-column">
	<h1>DV <del>CMS</del> Framework</h1>
	<div class="contents-list">
		<h3>Περιεχόμενα</h3>
		<ul>
			<li><?php echo a('Εισαγωγή', 'intro'); ?></li>
			<li><?php echo a('Πώς χρησιμοποιείται', 'howto'); ?></li>
			<li><?php echo a('Δομή καταλόγων', 'structure'); ?></li>
			<li>Αντικείμενα<ul>
				<li><?php echo a('Html', 'html'); ?></li>
				<li><?php echo a('Application', 'application'); ?></li>
				<li><?php echo a('Page', 'page'); ?></li>
				<li><?php echo a('File', 'file'); ?></li>
				<li><?php echo a('Db', 'db'); ?></li>
				<li><?php echo a('ActiveRecord', 'activeRecord'); ?></li>
			</ul></li>
			<li><?php echo a('Configuration', 'configuration'); ?></li>
			<li><?php echo a('Παράμετροι', 'parameters'); ?></li>
		</ul>
	</div>
	<div class="contents">
		<?php

			$html = '';
			$page = array_key_exists('page', $_GET) ? $_GET['page'] : 'intro';
			$page = str_replace(array('/', '\\'), array('', ''), $page);
			$inc = dirname(__FILE__) . '/' . $page . '.php';
			if (!file_exists($inc))
				throw new Exception('Documentation page not found! "' . $page . '"');
			
			include($inc);
			if (isset($title) && !empty($title))
				$html .= tag('h2', 'title', $title);
			if (isset($description) && !empty($description))
				$html .= tag('p', 'description', nl2br($description));
			if (isset($description_html) && !empty($description_html))
				$html .= $description_html;
			if (isset($properties) && !empty($properties))
			{
				foreach ($properties as $name => $value) {
					$html .= tag('p', 'property-name', nl2br(htmlspecialchars($name)));
					$html .= tag('p', 'property-description', nl2br(htmlspecialchars($value)));
				}
			}
			if (isset($properties_table) && !empty($properties_table))
			{
				$html .= '<table class="properties">';
				foreach ($properties_table as $name => $value) {
					$html .= '<tr>';
					$html .= tag('td', 'name', nl2br(htmlspecialchars($name)));
					$html .= tag('td', 'description', nl2br(htmlspecialchars($value)));
					$html .= '</tr>';
				}
				$html .= '</table>';
			}
			
			echo $html;
		?>
	</div>
	<div style="clear:both;"></div>
</div>
</body>
</html>