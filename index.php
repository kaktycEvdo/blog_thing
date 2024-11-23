<?php
    session_start();
    require 'connect_to_db.php';

    $dir = '/blog-project';
    $url = explode('?', $_SERVER['REQUEST_URI'])[0];
    $url = str_replace($dir, '',  $url);

    /**
     * Page class for displaying and handling things
     * @property string $dir Current directory. Leave empty if it's in hosting's root. By default equals to empty string.
     * @property string $url Current url with slash. Index is '/' and main is '/main', for example.
     * @property PDO $pdo A PDO object for working with server.
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
     * Modal class for server response throws.
     * @property bool $type Type of modal. 1 - error, 0 - success.
     * @property string $message Message of the modal. Best to keep under 100 characters.
     * @property-read bool $thrown An "if-thrown" parameter. Changes with functions.
     */
    class ServerModal{
        private $message;
        private $type;
        public $thrown;

        public function __construct() {
            $this->thrown = false;
        }

        private function changeMessage(string $message){
            $this->message = $message;
        }
        private function changeType(string $type){
            $this->type = $type;
        }

        public function changeModal(string $message, bool $error = false){
            $this->changeMessage($message);
            $this->changeType($error);
        }
        public function closeModal(){
            $this->thrown = false;
        }
        public function throwModal(string $message, bool $error = false, string $location = null){
            $this->changeModal($message, $error);
            $this->thrown = true;
            $modal = $this;
            $_SESSION['response'] = serialize($modal);
            if($location){
                header("Location: $location");
            }
        }
        public function printMessage(){
            switch($this->type){
                case false:
                    echo "<div class='modal msuccess'>$this->message</div>";
                    break;
                case true:
                    echo "<div class='modal merror'>$this->message</div>";
                    break;
            }
        }
    }

    $modal = null;
    if(isset($_SESSION['response']) && $_SESSION['response'] != null){
        $modal = unserialize($_SESSION['response']);
        if($modal->thrown){
            $modal->printMessage();
            $modal->closeModal();
        }
        $_SESSION['response'] = serialize($modal);
    }
    
    require_once 'models.php';

    switch ($url){
        case '/':
        case '/index':
        case '/main':
            include_once 'handlers/main.php';
            break;
        case '/profile':
            if(isset($_SESSION['user']) && $_SESSION['user'] != ''){
                include_once 'handlers/profile.php';
                $user = unserialize($_SESSION['user']);
                $_SESSION['left_user'] = serialize($user);
            } else {
                header('Location: auth');
            }
            break;
        case '/about':
            if(isset($_SESSION['user']) && $_SESSION['user'] != ''){
                include_once 'handlers/blogpage.php';
                $user = unserialize($_SESSION['user']);
                $_SESSION['left_user'] = serialize($user);
            } else {
                header('Location: auth');
            }
            break;
        case '/blogpage':
            if(isset($_GET['user']) || isset($_SESSION['user'])){
                if(!isset($_GET['user'])){
                    $user = unserialize($_SESSION['user']);
                    $_SESSION['left_user'] = serialize($user);
                    header('Location: about');
                }
                else{
                    $user = unserialize($_SESSION['user']);
                    $_SESSION['left_user'] = serialize($user);
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