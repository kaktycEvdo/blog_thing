<?php
if(!isset($_POST['name'])){
    include_once 'components/reg_form.html';
}
else{
    require_once 'checking_module.php';
    // check the data
    if(!isset($_POST['name']) || !isset($_POST['password']) || !isset($_POST['email'])){
        $_SESSION['response'] = [1, 'Ошибка: Пустые поля'];
        header('Location: reg');
        die;
    }

    $name = $_POST['name'];
    $email = $_POST['email'];

    $res = validateName($name);
    if($res[0] == 1){
        $_SESSION['response'] = $res;
        header('Location: reg');
        die;
    }
    $res = validateEmail($email);
    if($res[0] == 1){
        $_SESSION['response'] = $res;
        header('Location: reg');
        die;
    }

    $to = '';

    if(!isset($_FILES['pfp']) || $_FILES['pfp'] == 'default' || $_FILES['pfp']['name'] == ''){
        $to = 'static/user-default.png';
    } else {
        $to = 'static/user/'.$_POST['name'].'/'.$_FILES['pfp']['name'];
        if(!is_dir('static/user/'.$user_data['name'])){
            mkdir('static/user/'.$user_data['name']);
        }
        $img = $_FILES['pfp'];
    
        $imgVal = validateImage($img, $to);

        if($imgVal[0] == 1){
            $_SESSION['response'] = $imgVal;
            header('Location: reg');
            die;
        }
    }

    // do the thing

    $to = str_replace('static/', '', $to);
    $query = $mysql->prepare('INSERT INTO users(name, email, password, pfp) VALUES (:name, :email, :password, :pfp)');
    $query->bindValue('name', $_POST['name']);
    $query->bindValue('email', $_POST['email']);
    $query->bindValue('password', hash('sha256', $_POST['password']));
    $query->bindValue('pfp', $to);

    if(!$query->execute()){
        echo 'Ошибка создания пользователя';
        die;
    }

    $id = $mysql->query('SELECT id FROM users ORDER BY id DESC LIMIT 1')->fetch(PDO::FETCH_COLUMN);

    $_SESSION['user_id'] = $id;
    $_SESSION['left_user_id'] = $id;
    header('Location: profile');
}