<?php 
if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){
    $post = $_GET['blog_id'];
    $author = $_SESSION['user_id'];
    $text = $_POST['text'];

    $stmt = $mysql->prepare("INSERT INTO comment(author, post, text) VALUES (:author, :post, :text)");
    if(isset($_GET['response'])){
        $response = $_GET['response'];
        $stmt = $mysql->prepare("INSERT INTO comment(author, post, text, response) VALUES (:author, :post, :text, :response)");
        $stmt->bindParam('response', $response);
    }
    $stmt->bindParam('author', $author);
    $stmt->bindParam('text', $text);
    $stmt->bindParam('post', $post);

    $res = $stmt->execute();
    if($res){
        $_SESSION['response'] = [0, 'Комментарий успешно опубликован'];
    }
    else{
        $_SESSION['response'] = [0, 'Произошла ошибка при публикации комментария'];
    }
    header('Location: blog?id='.$post);
} else {
    header('Location: auth');
}