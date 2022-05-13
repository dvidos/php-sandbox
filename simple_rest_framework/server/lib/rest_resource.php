<?php

class REST_Resource {
	
	public function handle_call($method, $id, $data) {
		if ($method == 'GET' && empty($id))
		{
			return $this->list_all();
		}
		else if ($method == 'GET' && !empty($id))
		{
			return $this->load($id);
		}
		else if ($method == 'POST' && empty($id))
		{ 
			return $this->insert($data);
		}
		else if ($method == 'POST' && !empty($id))
		{ 
			return $this->update($data, $id);
		}
		else if ($method == 'DELETE')
		{
			return $this->delete($id);
		}

		return [500, ['message' => 'Bad request']];
	}
	
	protected function list_all() {
		return [500, ['message' => 'Not implemented']];
	}
	
	protected function load($id) {
		return [500, ['message' => 'Not implemented']];
	}
	
	protected function insert($data) {
		return [500, ['message' => 'Not implemented']];
	}
	
	protected function update($data, $id) {
		return [500, ['message' => 'Not implemented']];
	}
	
	protected function delete($id) {
		return [500, ['message' => 'Not implemented']];
	}
}

