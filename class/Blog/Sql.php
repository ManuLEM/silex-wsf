<?php

namespace Blog;

use PDO;

Class Sql
{

	public $pdo;
	public $server;
	public $database;
	public $id;
	public $password;

	/**
	 * [__construct description]
	 * @param string $server
	 * @param string $database
	 * @param string $id
	 * @param string $password
	 */
	public function __construct($server, $database, $id, $password)
	{
		$this->server = $server;
		$this->database = $database;
		$this->id = $id;
		$this->password = $password;

		$dsn = 'mysql:dbname='.$database.';host='.$server;
		$this->pdo = new PDO($dsn, $id, $password);
	}

	public function query($sql)
	{
		return $this->pdo->query($sql);
	}

	/**
	 * [prepareExec description]
	 * @param  string $sql
	 * @param  string $argument
	 * @return string
	 */
	public function prepareExec($sql, $argument)
	{
		$statement = $this->pdo->prepare($sql);
		$statement->execute($argument);

		return $statement;
	}

	public function lastId(){
		return $this->pdo->lastInsertId();
	}
}