<?php
class AdPage extends Page{
    public $page;

    function displayContent(){
        require_once 'view/advertisement.php';
    }
}
$cur_page = new AdPage($pdo, $dir);