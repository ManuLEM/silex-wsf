<?php

use Blog\Model;

class User extends Model
{
	public function checkEmail($email)
	{
		$sql = 'SELECT id, email, password, name, type, salt FROM users WHERE email = :email';
		$arguments = array(
			':email' => $email
		);

		return $this->app['sql']->prepareExec($sql, $arguments);
	}

	public function checkEmailExists($email)
	{
		$sql = "SELECT email FROM users WHERE email = :email";

		$arguments = array(
			':email' => $email
		);

		return $this->app['sql']->prepareExec($sql, $arguments);
	}

	public function insertUser($email, $password, $name)
	{
		$salt = uniqid();
		$password = sha1($password.$salt);

		$sql = "INSERT INTO users(
			email,
			password,
			name,
			type,
			salt
		)
		VALUES (
			:email, :password, :name, :type, :salt
		)";
		
		$arguments = array(
			':email' => $email,
			':password' => $password,
			':name' => $name,
			':type' => 'user',
			':salt' => $salt
		);
		$this->app['sql']->prepareExec($sql, $arguments);

		return $this->app['sql']->lastId();
	}
}