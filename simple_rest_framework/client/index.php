<?php

require('lib/rest_client.php');
require('lib/rest_proxy.php');
require('proxies/customer_proxy.php');


$customer_proxy = new Customer_Proxy(new REST_Client());

$response = $customer_proxy->insert(['name' => 'John Doe']);
$customer = $customer_proxy->load(1);
$response = $customer_proxy->update(['name' => 'Maximilian Smith'], 1);
$response = $customer_proxy->delete(1);

