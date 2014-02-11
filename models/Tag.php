<?php

use Blog\Model;

class Tag extends Model
{
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