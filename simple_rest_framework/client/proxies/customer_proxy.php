<?php

class Customer_Proxy extends REST_Proxy {
	public function __construct($proxy_client) {
		parent::__construct('customer', $proxy_client);
	}
}


