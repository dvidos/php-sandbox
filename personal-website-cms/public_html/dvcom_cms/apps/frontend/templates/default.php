<!DOCTYPE html>
<html>
<head>
	<link rel="icon" type="image/png" href="/favicon.png">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?php echo cms()->config['base_url']; ?>/resources/css/base.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo cms()->config['base_url']; ?>/resources/css/grid.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo cms()->config['base_url']; ?>/resources/css/colors.css" />
	<script type="text/javascript" src="<?php echo cms()->config['base_url']; ?>/resources/js/jquery-3.1.1.min.js"></script>
	<script type="text/javascript" src="<?php echo cms()->config['base_url']; ?>/resources/google-code-prettify/prettify.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo cms()->config['base_url']; ?>/resources/google-code-prettify/prettify.css" />
	<script type="text/javascript">$(document).ready(function(){ $("code").addClass("prettyprint"); PR.prettyPrint(); });</script>
</head>
<body>
<div id="header-wrapper"><header>

	<div id="login-info"><?php
		if (!empty($_SESSION['logged_in']) && !empty($_SESSION['user']))
		{
			$user = $_SESSION['user'];
			echo '<b>' . htmlspecialchars($user->email) . '</b>';
			echo ' | ';
			echo Html::link('Sign Out' , '/site/signOut');
		}
	?></div>
	<?php echo Html::link(cms()->config['site_title'], '/', ['id' => 'site-logo']); ?>
	<p id="site-subtitle"><?php echo htmlentities(cms()->config['site_subtitle']); ?></p>
	<p id="site_nav"><?php
		$links = [
			Html::link('Home', '/'),
			Html::link('About Dimitris', '/about'),
		];
		if (!empty($_SESSION['user']) && $_SESSION['user']->allow_admin) {
			$links[] = Html::link('Admin' , '/admin');
		}
		echo implode(' &nbsp;-&nbsp; ', $links);
	?></p>
</header></div>
<main>
	<?php echo $content; ?>
</main>
<footer>
	
	<p><?php
		echo Html::link('Copyright', '/copyright') . ' 2016-2017 Dimitris Vidos';
		echo ' &nbsp;-&nbsp; ';
		echo '<a href="#">Top</a>';
		if (empty($_SESSION['user'])) {
			echo ' &nbsp;-&nbsp; ' . Html::link('Sign in', '/site/signIn');
		}
	?></p>
	
</footer>
</body>
</html>
