<?php
$config = parse_ini_file('db.ini');
$mysql = new PDO("mysql:host=".$config['host'].";dbname=".$config['database'], $config['name'], $config['password']);

/**
 * @var PDO $pdo Current PDO object. Change to other InnoDB SCBD when needed.
 */
$pdo = $mysql;