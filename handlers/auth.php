<?php
class AuthPage extends Page{
    public $page;

    function displayContent(){
        $pdo = $this->pdo;
        require_once 'view/components/auth_form.html';
    }
}
$cur_page = new AuthPage($pdo, $dir);

if(isset($_POST['name'])){
    // check the data
    if(!isset($_POST['name']) || !isset($_POST['password'])){
        $modal->changeModal('Ошибка: пустые поля при авторизации', true);
        $modal->throwModal();
        die;
    }
    
    $name = $_POST['name'];
    $password = hash('sha256', $_POST['password']);

    // do the thing
    $user = unserialize($_SESSION['user']);

    $user->authorize();
}