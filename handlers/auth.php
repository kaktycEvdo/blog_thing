<?php
class AuthPage extends Page{
    public $page;

    function displayContent(){
        require_once 'view/components/auth_form.html';
    }
}
$cur_page = new AuthPage($pdo, $dir);

if(isset($_POST['name'])){
    // check the data
    if(!isset($_POST['name']) || !isset($_POST['password'])){
        $modal->throwModal('Ошибка: пустые поля при авторизации', true, 'auth');
        die;
    }
    
    $name = $_POST['name'];
    $password = hash('sha256', $_POST['password']);

    // do the thing
    $user = new User($name, $name, $password, $pdo);
    $user->authorize($pdo);
}