<?php
class MainPage extends Page{
    function displayContent(){
        $pdo = $this->pdo;
        require_once 'view/main.php';
    }
}
$cur_page = new MainPage($pdo);