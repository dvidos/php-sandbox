<?php ob_start(); ?>





<h1>Customers</h1>

<?php
	foreach ($customers as $cust)
	{
		$link = Html::link(Html::encode($cust['CompanyName']), array('/customers/' . $cust['CustomerID'])); 
		echo Html::tag('h2', array('style'=>'margin-bottom: 0;'), $link);
		echo $cust['ContactName'];
	}
?>



<?php app()->load_template(app()->config['default_template'], array('content'=>ob_get_clean())); ?>