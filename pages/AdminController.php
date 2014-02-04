<?php
	
	use Blog\Controller;

	Class AdminController extends Controller
	{
		/**
		 * [getArticle description]
		 * @return [type] [description]
		 */
		public function getArticle()
		{
			return $this->app['twig']->render('admin.twig');
		}


		/**
		 * [postArticle description]
		 * @return [type] [description]
		 */
		public function postArticle()
		{
			$title = $this->app['request']->get('title');
			$content = $this->app['request']->get('article');

			if (!empty($title) && !empty($content)) {
				$sql = "INSERT INTO articles (
					id,
					title,
					body
				)
				VALUES (
					NULL,
					:title,
					:content
				)";
				
				$arguments = array (
					':title' => $title,
					':content' => $content
				);

				$this->app['sql']->prepareExec($sql, $arguments);
			}

			return $this->getArticle();
		}
	}