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

    if(!isset($_FILES['pfp']) || $_FILES['pfp'] == 'default'){
        $to = 'static/user-default.png';
    } else {
        $to = 'static/user/'.$_POST['name'].'/'.$_FILES['pfp']['name'];
    }

    if(!is_dir('static/user/'.$user_data['name'])){
        mkdir('static/user/'.$user_data['name']);
    }
    
    if ($_FILES['pfp']['error'] > 0) {
        echo 'Проблема: ';
        switch ($_FILES['pfp']['error']) {
            case 1: echo 'Размер файла больше upload_max_filesize';
            break;
            case 2: echo 'Размер файла больше max_file_size';
            break;
            case 3: echo 'Загружена только часть файла';
            break;
            case 4: echo 'Файл не загружен';
            break;
            case 6: echo 'Загрузка невозможна: не задан временный каталог';
            break;
            case 7: echo 'Загрузка не выполнена: невозможна запись на диск';
            break;
        }
        die;
    }
    
    if ($_FILES['pfp']['type'] != 'image/jpg' &
    $_FILES['pfp']['type'] != 'image/jpeg' &&
    $_FILES['pfp']['type'] != 'image/png' &&
    $_FILES['pfp']['type'] != 'image/webp'){
        echo 'Файл не является изображением';
        die;
    }

    if (is_uploaded_file($_FILES['pfp']['tmp_name'])) {
        if (!move_uploaded_file($_FILES['pfp']['tmp_name'], $to)) {
            echo 'Проблема: невозможно переместить файл в каталог назначения';
            die;
        }
    } else {
        echo 'Проблема: возможна атака через загрузку файла. Файл: ';
        echo $_FILES['pfp']['name'];
        die;
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