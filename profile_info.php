<?php
$user = isset($_GET['user_id']) ? $_GET['user_id'] : (isset($_SESSION['user_id']) ? $_SESSION['user_id'] : -1);

$user_data = $mysql->query('SELECT name, email, bg, pfp FROM users WHERE id = '.$user, PDO::FETCH_ASSOC)->fetch();