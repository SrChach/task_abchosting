<?php

require_once 'model/Connection.php';
require_once 'config/env.php';

use model\Connection\Connection;

$connection = new Connection(HOST, PORT, DBNAME, USER, PASS);

$res = $connection->fetchAll("SELECT * FROM product");

header('Content-type: Application/json');
echo json_encode($res);
