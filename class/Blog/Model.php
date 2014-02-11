<?php

namespace Blog;

class Model
{
	public $app;
	public $sql;

	public function __construct($app)
	{
		$this->app = $app;
		$this->sql = $app['sql'];
	}
}