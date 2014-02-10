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
			if (!$this->isAdmin()) {
				return $app->redirect(
		            $app['url_generator']->generate('home')
		        );
			}

			$this->data['tags'] = $this->app['sql']->query('SELECT * FROM tags');
			return $this->app['twig']->render('admin.twig', $this->data);
		}


		/**
		 * [postArticle description]
		 * @return [type] [description]
		 */
		public function postArticle()
		{
			if (!$this->isAdmin()) {
				return $app->redirect(
		            $app['url_generator']->generate('home')
		        );
			}

			$title = $this->app['request']->get('title');
			$content = $this->app['request']->get('article');
			$tags = $this->app['request']->get('tags');

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

				$last = $this->app['sql']->lastId();

				foreach ($tags as $tag) {
					$query = "INSERT INTO articles_tags (
						id,
						id_articles,
						id_tag
					)
					VALUES (
						NULL,
						:id_articles,
						:tag
					)";

					$arguments = array (
						':id_articles' => $last,
						':tag' => $tag
					);
					$this->app['sql']->prepareExec($query, $arguments);
				}
			}

			return $this->getArticle();
		}

		public function getTags()
		{
			if (!$this->isAdmin()) {
				return $app->redirect(
		            $app['url_generator']->generate('home')
		        );
			}
			
			return $this->app['twig']->render('addTags.twig', $this->data);
		}

		public function postTags()
		{
			if (!$this->isAdmin()) {
				return $app->redirect(
		            $app['url_generator']->generate('home')
		        );
			}
			
			$tag = $this->app['request']->get('tag');

			if (!empty($tag)) {
				$sql = "INSERT INTO tags (
					id,
					name
				)
				VALUES (
					NULL,
					:name
				)";
				
				$arguments = array (
					':name' => $tag,
				);

				$this->app['sql']->prepareExec($sql, $arguments);
			}

			return $this->getTags();
		}
	}