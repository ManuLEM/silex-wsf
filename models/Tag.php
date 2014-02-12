<?php

use Blog\Model;

class Tag extends Model
{
	public function getTagsArticle($idArticle)
	{
		$sql = 'SELECT *
		FROM tags, articles_tags
		WHERE tags.id = articles_tags.id_tag
		AND articles_tags.id_articles = :id';

		$arg = array(
			':id' => $idArticle
		);

		return $this->app['sql']->prepareExec($sql, $arg)->fetchAll();
	}

	public function insertTag($tag)
	{
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
		
		return $this->app['sql']->lastId();
	}
}