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
    $query = $mysql->prepare('SELECT id FROM users WHERE name = :name and password = :password');
    $queryEmail = $mysql->prepare('SELECT id FROM users WHERE email = :name and password = :password');

    $name = $_POST['name'];
    $password = hash('sha256', $_POST['password']);

    if (str_contains($name, '@')){
        $queryEmail->bindParam('name', $name);
        $queryEmail->bindParam('password', $password);
        $queryEmail->execute();
    }
    else{
        $query->bindParam('name', $name);
        $query->bindParam('password', $password);
        $query->execute();
    }

    $idwn = $query->fetch(PDO::FETCH_COLUMN);
    $idwe = $queryEmail->fetch(PDO::FETCH_COLUMN);

    if($idwn){
        $_SESSION['user_id'] = $idwn;
        $_SESSION['left_user_id'] = $idwn;
    }
    else if ($idwe){
        $_SESSION['user_id'] = $idwe;
        $_SESSION['left_user_id'] = $idwe;
    }
    header('Location: profile');
}