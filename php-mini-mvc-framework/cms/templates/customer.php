<?php app()->begin_embedding(); ?>





<h1><?php echo Html::encode($customer['CompanyName']); ?></h1>

<h2><?php echo Html::encode($customer['ContactName']); ?></h2>

<p><?php echo Html::encode($customer['Address']); ?><br />
<?php echo Html::encode($customer['City']); ?>, <?php echo Html::encode($customer['State']); ?></p>





<?php app()->end_embedding(); ?>