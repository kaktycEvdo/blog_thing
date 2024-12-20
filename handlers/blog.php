<?php
class BlogPage extends Page{
    function displayContent(){
        if(!isset($_GET['id'])){
            $this->redirect($this->dir);
        }
        $pdo = $this->pdo;
        $url = $this->url;

        if(isset($_GET['user']) && preg_match('/\d+/', $_GET['user'])){
            $luser_id = $_GET['user'];

            $stmt = $pdo->prepare("SELECT id, name, email, description, brief, pfp, background FROM users WHERE id = :id");
            $stmt->bindParam("id", $luser_id);
            $stmt->execute();

            $luser_data = $stmt->fetch();

            $luser = new User(
                $luser_data['name'],
                $luser_data['email'],
                '',
                $pdo,
                $luser_data['pfp'],
                $luser_data['background'],
                $luser_data['description'],
                $luser_data['brief'],
                $luser_data['id']
            );

            $_SESSION['left_user'] = serialize($luser);

            if(isset($_POST['type'])){
                include_once 'create_post.php';
            }
            else{
                require_once 'view/blog.php';
            }
        }
        else if(isset($_SESSION['left_user'])){
            $luser = unserialize($_SESSION['left_user']);
            if(isset($_POST['type'])){
                include_once 'create_post.php';
            }
            else{
                require_once 'view/blog.php';
            }
        }
        else{
            header("Location: auth");
        }
    }
}
$cur_page = new BlogPage($pdo);