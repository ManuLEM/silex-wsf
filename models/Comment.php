<?php

use Blog\Model;

class Comment extends Model
{
	public function saveComment($articleId, $comment, $userId)
	{
		$sql = 'INSERT INTO comments (id, content) VALUES (NULL, :comment)
		';

		$arg = array(
			':comment' => $comment
		);

		$this->app['sql']->prepareExec($sql, $arg)->fetchAll();

		$query = 'INSERT INTO comments_users_articles (
				id,
				id_comment,
				id_user,
				id_article
			)
			VALUES (
				NULL,
				:commentId,
				:userId,
				:articleId
			)
		';

		$argument = array(
			':commentId' => $this->app['sql']->lastId(),
			':userId' => $userId,
			':articleId' => $articleId
		);

		$this->app['sql']->prepareExec($query, $argument)->fetchAll();
	}

	public function getComments($articleId)
	{
		$sql = 'SELECT *
		FROM comments, comments_users_articles
		WHERE comments.id = comments_users_articles.id_comment
		AND comments_users_articles.id_article = :id';

		$arg = array(
			':id' => $articleId
		);

		return $this->app['sql']->prepareExec($sql, $arg)->fetchAll();
	}
}