<?php
	require_once __DIR__.'/../vendor/autoload.php'; 

	$app = new Silex\Application();

	$app['debug'] = true;

	require_once __DIR__.'/../config/database.php';
	$app['sql'] = new Blog\Sql(
		$config['server'],
		$config['database'],
		$config['id'],
		$config['password']
	);

	$app->register(new Silex\Provider\TwigServiceProvider(), array(
	    'twig.path' => __DIR__.'/../views',
	));

	$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

	$app->register(new Silex\Provider\SessionServiceProvider());

	$app->get('/', function() use($app) { 
	    $c = new HomeController($app);
		return $c->renderArticles();
	})
	->bind('home');


	$app->get('/admin', function() use($app) {
		$c = new AdminController($app);
		if ($app['session']->get('user')['type'] !== 'admin') {
			return $app->redirect(
	            $app['url_generator']->generate('home')
	        );
		}
		else{
			return $c->getArticle();
		}
	})
	->bind('getAdmin');

	$app->post('/admin', function() use($app) { 
		$c = new AdminController($app);
		return $c->postArticle();
	})
	->bind('postAdmin');

	$app->get('/login', function() use($app) { 
		$c = new UserController($app);
		return $c->getLogin();
	})
	->bind('login');
	
	$app->post('/login', function() use($app) { 
		$c = new UserController($app);
		return $c->postLogin();
	})
	->bind('postLogin');

	$app->get('/register', function() use($app) { 
		$c = new UserController($app);
		return $c->getRegister();
	})
	->bind('register');

	$app->post('/register', function() use($app) { 
		$c = new UserController($app);
		return $c->postRegister();
	})
	->bind('postRegister');

	$app->get('/tagsearch/{tagId}', function($tagId) use($app) { 
		$c = new UserController($app);
		return $c->getRegister();
	})
	->bind('tagSearch');

	$app->run();