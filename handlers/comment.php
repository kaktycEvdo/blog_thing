<?php
if(isset($_SESSION['user']) && $_SESSION['user'] != null){
    $post = $_GET['blog_id'];
    $author = unserialize($_SESSION['user']);
    $text = $_POST['text'];

    $stmt = $mysql->prepare("INSERT INTO comment(author, post, text) VALUES (:author, :post, :text)");
    if(isset($_GET['response'])){
        $response = $_GET['response'];
        $stmt = $mysql->prepare("INSERT INTO comment(author, post, text, response) VALUES (:author, :post, :text, :response)");
        $stmt->bindParam('response', $response);
    }
    $stmt->bindParam('author', $author->id);
    $stmt->bindParam('text', $text);
    $stmt->bindParam('post', $post);

    $res = $stmt->execute();
    $modal = new ServerModal();
    if($res){
        $modal->throwModal('Комментарий успешно опубликован', false, "blog?id=$post");
    }
    else{
        $modal->throwModal('Комментарий успешно опубликован', true, "blog?id=$post");
    }
} else {
    header('Location: auth');
}