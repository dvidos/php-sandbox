<?php

class REST_Proxy {
	protected $purest_client;
	protected $resource_name;
	
	public function __construct($resource_name, $purest_client) {
		$this->resource_name = $resource_name;
		$this->purest_client = $purest_client;
	}
	
	public function list_all($id) {
		return $this->client->get($this->resource_name, null);
	}
	
	public function load($id) {
		return $this->client->get($this->resource_name, $id);
	}
	
	public function insert($resource, $data) {
		return $this->client->post($this->resource_name, $data, null);
	}
	
	public function update($resource, $data, $id) {
		return $this->client->post($this->resource_name, $data, $id);
	}
	
	public function delete($id) {
		return $this->client->delete($this->resource_name, $id)
	}
}


