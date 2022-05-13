<?php


class REST_Dispatcher {
	
	protected $registered_resources = [];
	
	public function register_resource(string $resource_name, REST_Resource $resource) {
		$this->registered_resources[$resource_name] = $resource;
	}
	
	public function handle_call() {
		
		$method = $_SERVER['HTTP_METHOD'];
		$path_info = trim($_SERVER['PATH_INFO'], '/');
		list($resource_name, $id) = explode('/', $path_info);
		
		$request_body = file_get_contents('php://input');
		$data = json_decode($request_body);
		
		$resource = $this->registered_resources[$resource_name];
		
		try {
			list($response_code, $content) = $resource->handle_call($method, $id, $data);
		}
		catch (Exception $e) {
			response_code(500);
			echo 'Sorry, an error occured';
			return;
		}
		
		response_code($response_code);
		echo json_encode($content);
	}
}

