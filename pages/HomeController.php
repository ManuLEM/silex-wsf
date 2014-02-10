<?php


use Blog\Controller;

	Class HomeController extends Controller
	{
		public function renderArticles()
		{
			$data = array();
			$data['user'] = $this->isLogged();

			$query = $this->app['sql']->query('SELECT
		            articles.id as articlesId,
		            title,
		            body,
		            tags.id as tagsId,
		            tags.name
		        FROM articles
		        LEFT JOIN articles_tags
		        ON articles.id = articles_tags.id_articles
				LEFT JOIN tags
				ON articles_tags.id_tag = tags.id
				ORDER BY articlesId
		        ');

			$this->data['articles'] = array();
			
			$rows = $query->fetchAll(PDO::FETCH_ASSOC);

			foreach ($rows as $row) {
				$this->data['articles'][$row['articlesId']]['title'] = $row['title'];
				$this->data['articles'][$row['articlesId']]['body'] = $row['body'];
				$this->data['articles'][$row['articlesId']]['tags'][$row['tagsId']] = $row['name'];
			}

			return $this->app['twig']->render('home.twig', $this->data);
		}

		public function tagSearch($tagId)
		{
			$sql = 'SELECT
		            articles.id as articlesId,
		            title,
		            body,
		            tags.id as tagsId,
		            tags.name
		        FROM articles
		        LEFT JOIN articles_tags
		        ON articles.id = articles_tags.id_articles
				LEFT JOIN tags
				ON articles_tags.id_tag = tags.id
				WHERE tags.id = :tagId
				ORDER BY articlesId
		        ';

	        $arg = array(':tagId' => $tagId);
			$query = $this->app['sql']->prepareExec($sql, $arg);
			
			$this->data['tagSearch'] = $query->fetchAll(PDO::FETCH_ASSOC);
			return $this->app['twig']->render('tagSearch.twig', $this->data);
		}

		public function renderArticle($articleId)
		{
			$sql = 'SELECT
		            articles.id as articlesId,
		            title,
		            body,
		            tags.id as tagsId,
		            tags.name
		        FROM articles
		        LEFT JOIN articles_tags
		        ON articles.id = articles_tags.id_articles
				LEFT JOIN tags
				ON articles_tags.id_tag = tags.id
				WHERE articles.id = :articleId
		        ';

	        $arg = array(':articleId' => $articleId);
			$query = $this->app['sql']->prepareExec($sql, $arg);

			$rows = $query->fetchAll(PDO::FETCH_ASSOC);

			foreach ($rows as $row) {
				$this->data['article']['title'] = $row['title'];
				$this->data['article']['body'] = $row['body'];
				$this->data['article']['tags'][$row['tagsId']] = $row['name'];
			}
			
			return $this->app['twig']->render('article.twig', $this->data);
		}
	}
		