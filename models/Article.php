<?php

use Blog\Model;

class Article extends Model
{
	public function getAllArticles()
	{
		$query = $this->sql->prepareExec('SELECT
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
			ORDER BY articlesId DESC
	        ');

		$this->data['articles'] = array();
		
		$rows = $query->fetchAll(PDO::FETCH_ASSOC);

		$articlesProcessed = array();
		foreach ($rows as $row) {
			$articlesProcessed[$row['articlesId']]['title'] = $row['title'];
			$articlesProcessed[$row['articlesId']]['body'] = $row['body'];
			if (!empty($row['tagsId'])) {
				$articlesProcessed[$row['articlesId']]['tags'][$row['tagsId']] = $row['name'];
			}
		}

		return $articlesProcessed;
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
		
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}

	public function getArticle($articleId)
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

		$retrievedArticle = array();

		foreach ($rows as $row) {
			$retrievedArticle['title'] = $row['title'];
			$retrievedArticle['body'] = $row['body'];
			$retrievedArticle['tags'][$row['tagsId']] = $row['name'];
		}
		
		return $retrievedArticle;
	}

	public function getArticleForm()
	{
		return $this->app['sql']->query('SELECT * FROM tags');
	}

	public function insertArticle($title, $content, $tags)
	{
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

		return $this->getArticleForm();
	}
}