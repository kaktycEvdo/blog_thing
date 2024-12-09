<?php
class RegPage extends Page{
    public $page;

    function displayContent(){
        require_once 'view/components/reg_form.html';
    }
}
$cur_page = new RegPage($pdo);

if(isset($_POST['name'])){
    require_once 'checking_module.php';
    // check the data
    $modal = new ServerModal;
    if((!isset($_POST['name']) || $_POST['name'] == '')
    || (!isset($_POST['password']) || $_POST['password'] == '')
    || (!isset($_POST['email']) || $_POST['email'] == '')){
        $modal->throwModal('Ошибка: пустые поля при регистрации', true, 'reg');
        die;
    }

    $name = $_POST['name'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $res = validateName($name);
    if($res[0] == 1){
        $modal->throwModal($res[1], $res[0], 'reg');
        die;
    }
    $res = validateEmail($email);
    if($res[0] == 1){
        $modal->throwModal($res[1], $res[0], 'reg');
        die;
    }
    
    $name = $_POST['name'];
    $password = hash('sha256', $_POST['password']);
    $pfp = $_FILES['pfp'];
    
    if(!isset($pfp) || $pfp == 'default' || $pfp['name'] == ''){
        $to = 'static/user-default.png';
        $pfp = null;
    } else {
        $to = "static/user/$name/".$pfp['name'];
        if(!is_dir("static/user/$name")){
            mkdir("static/user/$name");
        }

        $imgVal = validateMedia($pfp, $to);

        if($imgVal[0] == 1){
            echo $imgVal[1].'<br>';
            echo $imgVal[0];
            $modal->throwModal($imgVal[1], $imgVal[0], 'reg');
            die;
        }
    }

    // do the thing
    $user = new User($name, $email, $password, $pdo, $pfp['name']);
    $user->register($pdo);
}