<?php

class Customer_Resource extends REST_Resource {
	
	protected function list_all() {
		// look in database
		$customers_list = [1, 2, 3, 4];
		return [200, $customers_list];
	}
	
	protected function load($id) {
		// look in database
		$customer = ['name' => 'James Smith', 'address' => '12 Sussex st.'];
		return [200, $customer];
	}
	
	protected function insert($data) {
		// insert in database
		return [200, ['message' => 'Customer created', 'id' => 1]];
	}
	
	protected function update($data, $id) {
		// update in database
		return [200, ['message' => 'Customer updated']];
	}
	
	protected function delete($id) {
		// delete from database
		return [200, ['message' => 'Customer deleted']];
	}
}
