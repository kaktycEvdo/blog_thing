<?php
class BlogPage extends Page{
    function displayContent(){
        $pdo = $this->pdo;
        $url = $this->url;
        if(isset($_POST['type'])){
            include_once 'create_post.php';
        }
        else{
            require_once 'view/blogpage.php';
        }
    }
}
$cur_page = new BlogPage($pdo, $dir);
?>