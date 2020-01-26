<?php

require_once dirname(__FILE__) . '/../config/env.php';

class Connection {

	public $schema;
	public $host;
	public $conn;

	public function __construct($host = HOST, $port = PORT, $dbname = DBNAME, $user = USER, $password = PASS) {
		if ( !isset($host, $port, $dbname, $user, $password) )
			return [ 'error' => 'faltan parámetros' ];

		$this->schema = $dbname;
		$this->host = $host;

		try {
            $conn = new PDO("mysql:host=$host;port=$port;dbname=$dbname;charset=utf8", $user, $password);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
            $this->conn = $conn;
		} catch (PDOException $e){
			echo $e->getMessage();
		}
	}

	public function fetchAll ($query, $params = null) {
		$stmt = $this->conn->prepare($query);
		if ($params == null)
			$stmt->execute();
		else
			$stmt->execute($params);

		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $res;
	}

	public function fetchOne ($query, $params = null) {
		$stmt = $this->conn->prepare($query);

		if ($params == null)
			$stmt->execute();
		else
			$stmt->execute($params);

		$res = $stmt->fetch(PDO::FETCH_ASSOC);

		if (!empty($res))
			return $res;
		return null;
	}

	public function exec ($query) {
		$affected = $this->conn->exec($query);

		if ($affected !== false)
			return $affected;
		die ($stmt->errorInfo()[2]);
	}

	public function execReturnId ($query) {
		$affected = $this->exec($query);

		if($affected != 0)
			return $this->conn->lastInsertId();
		return null;
	}

	public function execute ($query, $params = null) {
		$stmt = $this->conn->prepare($query);

		if ($params == null)
			$stmt->execute();
		else
			$stmt->execute($params);

		return $stmt->rowCount();
	}

	public function executeReturnId ($query, $params = null) {
		$affected = $this->execute($query, $params);

		if($affected)
			return $this->conn->lastInsertId();
		return null;
	}

}