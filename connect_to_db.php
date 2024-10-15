<?php
$config = parse_ini_file('db.ini');
$mysql = new PDO("mysql:host=".$config['host'].";dbname=".$config['database'], $config['name'], $config['password']);