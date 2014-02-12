<?php


use Blog\Controller;

	Class HomeController extends Controller
	{
		public function renderArticles()
		{
			$this->data['user'] = $this->isLogged();

			$article = new Article($this->app);

			$this->data['articles'] = $article->getAllArticles();

			return $this->app['twig']->render('home.twig', $this->data);
		}

		public function tagSearch($tagId)
		{
			$this->data['user'] = $this->isLogged();

			$article = new Article($this->app);

			$this->data['tagSearch'] = $article->tagSearch($tagId);

			return $this->app['twig']->render('tagSearch.twig', $this->data);
		}

		public function renderArticleById($articleId)
		{
			$this->data['user'] = $this->isLogged();

			$article = new Article($this->app);

			$this->data['article'] = $article->getArticle($articleId);

			$tag = new Tag($this->app);
			$tags = $tag->getTagsArticle($articleId);

			$this->data['tags'] = $tags;

			return $this->app['twig']->render('article.twig', $this->data);
		}
	}
		