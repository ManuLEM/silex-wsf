<?php
	
	namespace Blog;

	use PDO;

	Class Controller
	{
		public $app;
		public $data = array();

		public function __construct($app)
		{
			$this->app = $app;

			$this->data['user'] = $this->isLogged();
			$this->data['type'] = $this->isAdmin();
			
		}

		public function isLogged()
		{
			$user = $this->app['session']->get('user');

			return empty($user) ? false : $user;
		}

		public function isAdmin()
		{
			$user = $this->isLogged();

			$sql = 'SELECT type FROM users WHERE id = :id';
			$arg = array(
				':id' => $user['id']
			);

			$result = $this->app['sql']->prepareExec($sql, $arg)->fetch(PDO::FETCH_ASSOC);
			return $result['type'];
		}

		public function redirect($route, $data = null)
		{	
			if ($data) {
				return $this->app->redirect(
		            $this->app['url_generator']->generate($route, $data)
		        );
			}
			return $this->app->redirect(
	            $this->app['url_generator']->generate($route)
	        );
		}
	}