<?php

require_once('lib/rest_dispatcher.php');
require_once('lib/rest_resource.php');
require_once('resources/customer_resource.php');

$rest_dispatcher = new REST_Dispatcher();
$rest_dispatcher->register_resource('customer', new Customer_Resource());
$rest_dispatcher->handle_call();


