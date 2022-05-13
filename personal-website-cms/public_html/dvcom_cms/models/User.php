<?php


class User extends Model
{
	public function __construct()
	{
		$this->tablename = 'users';
		
		$this->id = 0;
		$this->email = '';
		$this->password_hash = '';
		$this->allow_admin = 0;
		$this->last_login_at = '';
		$this->created_at = date('Y-m-d H:i:s');
		$this->updated_at = '';
	}
	
	public function encrypt($password)
	{
		// use BLOWFISH to generate a strong salt and hash the user's password.
		// this will be 60 characters long, so plan accordingly.
		return password_hash($password, PASSWORD_BCRYPT);
	}
	
	public function verify($password)
	{
		// verifies the salted, hashed password
		return password_verify($password, $this->password_hash);
	}
}