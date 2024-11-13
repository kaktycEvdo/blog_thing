<?php
$post_id = $_GET['id'];

$postQ = $mysql->prepare('SELECT pinned FROM posts WHERE id = :id and author = '.$_SESSION['user_id']);
$postQ->bindParam('id', $post_id);
$postQ->execute();
$post = $postQ->fetch();

if($post['pinned'] == 1){
    $pinQ = $mysql->prepare('UPDATE posts SET pinned = 0 WHERE id = :id and author = '.$_SESSION['user_id']);
    $pinQ->bindParam('id', $post_id);
    $pinQ->execute();

    $_SESSION['response'] = [0, 'Пост убран из закрепленных'];
    header('Location: about');   
}
else if ($post['pinned'] == 0){
    $pinQ = $mysql->prepare('UPDATE posts SET pinned = 1 WHERE id = :id and author = '.$_SESSION['user_id']);
    $pinQ->bindParam('id', $post_id);
    $pinQ->execute();

    $_SESSION['response'] = [0, 'Пост закреплен'];
    header('Location: about');
}
else{
    $_SESSION['response'] = [1, 'Ошибка: Несуществующий пост'];
    header('Location: about');
}