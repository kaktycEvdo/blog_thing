<?php
$post_id = $_GET['id'];

$pinQ = $mysql->prepare('UPDATE posts SET pinned = 1 WHERE id = :id and author = '.$_SESSION['user_id']);
$pinQ->bindParam('id', $post_id);
$pinQ->execute();

$_SESSION['response'] = [0, 'Пост закреплен'];
header('Location: about');