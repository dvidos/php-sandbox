<?php

class REST_Client {
	protected $base_url;
	
	public function __construct($base_url = '') {
		$this->base_url = $base_url;
	}
	
	public function get($resource, $id) {
		return $this->make_rest_call('GET', $resource, $id);
	}
	
	public function post($resource, $data, $id) {
		return $this->make_rest_call('POST', $resource, $id, $data);
	}
	
	public function delete($resource, $id) {
		return $this->make_rest_call('DELETE', $resource, $id);
	}
	
	protected function make_rest_call($method, $resource, $id = null, $data = null) {
		$url = $this->base_url . '/' . urlencode($resource) . '/';
		if (!empty($id)) {
			$url .= urlencode($id);
		}
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// ...
		$output = curl_exec($ch);
		curl_close($ch);
	}
}


