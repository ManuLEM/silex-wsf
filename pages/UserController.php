<?php

use Blog\Controller;

Class UserController extends Controller
{
	public function getLogin()
	{
		return $this->app['twig']->render('user/login.twig', $this->data);
	}

	public function postLogin()
	{
		$email =$this->app['request']->get('email');
		$password =$this->app['request']->get('password');

		$sql = 'SELECT id, email, password, name, type, salt FROM users WHERE email = :email';
		$arguments = array(
			':email' => $email
		);

		$user = $this->app['sql']->prepareExec($sql, $arguments);
		$user = $user->fetch(); 
		// test if user exists
		if ($user === false) {
			$this->data['errors'] = 'Login or password incorrect';
		}

		// test if password is correct
		if (sha1($password.$user['salt']) !== $user['password']) {
			$this->data['errors'] = 'Login or password incorrect';
		}

		if (!empty($this->data['errors'])) {
			return $this->getLogin();
		}

		$user = array(
			'id' => $user['id'],
			'name' => $user['name'],
			'email' => $user['email'],
			'type' => $user['type']
		);
		
		$this->app['session']->set('user', $user);

		return $this->app->redirect(
            $this->app['url_generator']->generate('home')
        );

	}

	public function getRegister()
	{
		return $this->app['twig']->render('user/register.twig', $this->data);
	}

	public function postRegister()
	{
		$email = $this->app['request']->get('email');
		$password = $this->app['request']->get('password');
		$password_confirmation = $this->app['request']->get('password_confirmation');
		$name = $this->app['request']->get('name');

		// Vérification password
		if ($password !== $password_confirmation) {
			$this->data['errors'][] = 'Password doesn\'t match';
		}


		// Vérification email
		$sql = "SELECT email FROM users WHERE email = :email";

		$arguments = array(
			':email' => $email
		);

		$emailVerif = $this->app['sql']->prepareExec($sql, $arguments);
		
		if ($emailVerif->fetch() !== false) {
			$this->data['errors'][] = 'This email already exists.';
		}

		if (!empty($this->data['errors'])) {
			return $this->getRegister();
		}

		// Insertion dans la BDD
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

		return $this->app['twig']->render('user/register-success.twig', $this->data);
	}
}