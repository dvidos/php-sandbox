<?php
include('cms/loader.php');




app()->route('/', function() { 
	app()->handle_page('index'); 
});

app()->route('/scaffold', function() {
	$scaffold = new Scaffold('scaffold');
	$scaffold->run();
});

app()->route('/{en|gr}/categories/{\d+}/{\w+}.html', function($lang, $cat, $title) {
	echo "lang is '$lang', cat is '$cat' and title is '$title'";
});

app()->route('/customers', function() {
	$custs = app()->db->findAll('customers');
	app()->log_debug('customers is ' . var_export($custs, true));
	app()->load_template('customers', array('customers'=>$custs));
});

app()->route('/customers/{\d+}', function($id) {
	$cust = app()->db->find('customers', array(
		'condition'=>'CustomerId = :cid',
		'params'=>array(':cid'=>$id),
	));
	if ($cust == null)
		app()->load_template('404');
	else
		app()->load_template('customer', array('customer'=>$cust));
});


// finaly, a catch-all
app()->route('/{.+}', function($url) {
	app()->handle_page($url);
});


app()->run();


