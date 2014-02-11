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

		$users = new User($this->app);

		$user = $users->checkEmail($email)->fetch(); 
		// test if user exists
		if ($user === false) {
			$this->data['errors'] = 'Login or password incorrect';
		}

		// test if password is correct
		if (sha1($password.$user['salt']) !== $user['password']) {
			$this->data['errors'] = 'Login or password incorrect';
		}

		if (!empty($this->data['errors'])) {
			return $this->redirect('home');
		}

		$user = array(
			'id' => $user['id'],
			'name' => $user['name'],
			'email' => $user['email']
		);
		
		$this->app['session']->set('user', $user);

		return $this->redirect('home');

	}

	public function getLogout()
	{
		$this->app['session']->set('user', null);

		return $this->redirect('home');
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
		$verif = new User($this->app);

		$emailVerif = $verif->checkEmailExists($email);
		
		if ($emailVerif->fetch() !== false) {
			$this->data['errors'][] = 'This email already exists.';
		}

		if (!empty($this->data['errors'])) {
			return $this->getRegister();
		}


		// Insertion dans la BDD
		$users = new User($this->app);

		$users->insertUser($email, $password, $name);

		return $this->redirect('home');
	}
}