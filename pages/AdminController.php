<?php
	
	use Blog\Controller;

	Class AdminController extends Controller
	{
		/**
		 * [getArticleForm description]
		 * @return [type] [description]
		 */
		public function getArticleForm()
		{
			if (!$this->isAdmin()) {
				return $this->app->redirect(
		            $this->app['url_generator']->generate('home')
		        );
			}

			$article = new Article($this->app);

			$this->data['tags'] = $article->getArticleForm();

			return $this->app['twig']->render('admin.twig', $this->data);
		}


		/**
		 * [postArticle description]
		 * @return [type] [description]
		 */
		public function postArticle()
		{
			if (!$this->isAdmin()) {
				return $this->app->redirect(
		            $this->app['url_generator']->generate('home')
		        );
			}

			$title = $this->app['request']->get('title');
			$content = $this->app['request']->get('article');
			$tags = $this->app['request']->get('tags');

			$article = new Article($this->app);

			$this->data['tags'] = $article->insertArticle($title, $content, $tags);

			return $this->redirect('home');
		}

		public function getTags()
		{
			if (!$this->isAdmin()) {
				return $this->app->redirect(
		            $this->app['url_generator']->generate('home')
		        );
			}
			
			return $this->app['twig']->render('addTags.twig', $this->data);
		}

		public function postTags()
		{
			if (!$this->isAdmin()) {
				return $this->app->redirect(
		            $this->app['url_generator']->generate('home')
		        );
			}
			
			$tagAdded = $this->app['request']->get('tag');

			if (!empty($tagAdded)) {
				$tag = new Tag($this->app);

				$tag->insertTag($tagAdded);
			}

			return $this->redirect('home');
		}
	}