<?php
if(!isset($_POST['name'])){
    include_once 'components/auth_form.html';
}
else{
    // check the data
    if(!isset($_POST['name']) || !isset($_POST['password'])){
        echo 'fields were empty';
        die;
    }

    // do the thing
    $query = $mysql->prepare('SELECT id FROM users WHERE name = :name and password = :password ORDER BY id DESC LIMIT 1');
    $query->bindParam('name', $_POST['name']);
    $query->bindParam('password', hash('sha256', $_POST['password']));

    $id = $query->execute()->fetch(PDO::FETCH_COLUMN);

    $_SESSION['user_id'] = $id;
    $_SESSION['left_user_id'] = $id;
    header('Location: profile');
}