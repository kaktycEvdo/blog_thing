<?php
    session_start();
    require 'connect_to_db.php';
    require_once 'profile_info.php';

    $dir = '/blog-project';
    $url = explode('?', $_SERVER['REQUEST_URI'])[0];
    $url = str_replace($dir, '',  $url);

    /**
     * Page class for displaying and handling things
     * @param string $dir = '' Current directory. Leave empty if it's in hosting's root
     * @var string $url Current url with slash. Index is '/' and main is '/main', for example.
     */
    class Page{
        protected $url;
        protected $pdo;
        protected $dir;

        public function __construct(PDO $pdo, string $dir = '', string $url = '') {
            $this->pdo = $pdo;
            $this->dir = $dir;
            if($url == ''){
                $url = explode('?', str_replace($dir, '',  $_SERVER['REQUEST_URI'])[0]);
            }
            $this->url = $url;
        }
        private function displayHeader(){
            include_once 'view/components/header.php';
        }
        protected function displayContent(){}
        public function display(){
            echo '<main>';
            echo '<section>';
            $this->displayHeader();
            $this->displayContent();
            echo '</section>';
            include_once 'view/components/leftpanel.php';
            echo '</main>';
        }
    }
    /**
     * Displays server response modal
     * @return void Echoes the modal.
     */
    function displayServerModal(){
        if(isset($_SESSION['response'])){
            $res = $_SESSION['response'];
            echo '<div class="modal '.($res[0] ? "merror" : "msuccess").'">'.$res[1].'</div>';
            $_SESSION['response'] = null;
        }
    }

    switch ($url){
        case '/':
        case '/index':
        case '/main':
            include_once 'handlers/main.php';
            break;
        case '/profile':
            if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){
                include_once 'handlers/profile.php';
                $_SESSION['left_user_id'] = $_SESSION['user_id'];
            } else {
                header('Location: auth');
            }
            break;
        case '/about':
            if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){
                include_once 'handlers/blogpage.php';
                $_SESSION['left_user_id'] = $_SESSION['user_id'];
            } else {
                header('Location: auth');
            }
            break;
        case '/blogpage':
            if(isset($_GET['user']) || isset($_SESSION['user_id'])){
                if(!isset($_GET['user'])){
                    $_SESSION['left_user_id'] = $_SESSION['user_id'];
                    header('Location: about');
                }
                else{
                    $_SESSION['left_user_id'] = $_GET['user'];
                    include_once 'handlers/blogpage.php';
                }
            }
            else{
                header('Location: ../'.$dir);
            }
            break;
        case '/blog':
            include_once 'handlers/blog.php';
            break;
        case '/reg':
            include_once 'handlers/reg.php';
            break;
        case '/auth':
            include_once 'handlers/auth.php';
            break;
        case '/create_post':
            include_once 'create_post.php';
            break;
        case '/pin_post':
            include_once 'pin_post.php';
            break;
        case '/comment':
            include_once 'comment.php';
            break;
        case '/works':
            include_once 'handlers/works.php';
            break;
        default:
            include_once 'handlers/404.html';
            break;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>БлогPost</title>
    <meta name="description" content="Бесплатный сайт по ведению собственных блогов. Блог – вид текстового, изобразительного и видео-контента, который позволяет делиться впечатлениями из собственной жизни.">
    <link rel="stylesheet" href="static/main.css">
</head>
<body>
<script src="static/main.js"></script>
<?php
    $cur_page->display();
?>
</body>
</html>