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
		$sql = 'SELECT
	            comments.id,
	            content,
	            users.name as username
	        FROM comments
	        LEFT JOIN comments_users_articles
	        ON comments_users_articles.id_comment = comments.id
			LEFT JOIN users
			ON comments_users_articles.id_user = users.id
			LEFT JOIN articles
			ON comments_users_articles.id_article = articles.id
			WHERE articles.id = :articleId
			ORDER BY comments.id
		';

		$arg = array(
			':articleId' => $articleId
		);

		return $this->app['sql']->prepareExec($sql, $arg)->fetchAll();
	}

	public function getAllComments()
	{
		$sql = 'SELECT
	            comments.id as commentId,
	            content,
	            users.name as username,
	            articles.title as title,
	            articles.id as articleId
	        FROM comments
	        LEFT JOIN comments_users_articles
	        ON comments_users_articles.id_comment = comments.id
			LEFT JOIN users
			ON comments_users_articles.id_user = users.id
			LEFT JOIN articles
			ON comments_users_articles.id_article = articles.id
			ORDER BY comments.id
		';

		return $this->app['sql']->prepareExec($sql)->fetchAll();
	}

	public function removeComment( $commentIds = array() )
	{
		foreach ($commentIds as $comment => $id) {
			$sql = 'DELETE FROM comments WHERE id = :commentId';

			$arg = array(
				':commentId' => $id
			);

			$this->app['sql']->prepareExec($sql, $arg)->fetchAll();

			$query = 'DELETE FROM comments_users_articles WHERE id_comment = :commentId';

			$argument = array(
				':commentId' => $id
			);

			$this->app['sql']->prepareExec($query, $argument)->fetchAll();
		}
	}
}