<?php
    session_start();
    require 'connect_to_db.php';

    $url = explode('?', $_SERVER['REQUEST_URI'])[0];
    $dir = '/'.explode('/', $url)[1];
    $url = str_replace($dir, '',  $url);

    // maybe change to something else later cause it's really unoptimised
    $_SESSION['dir'] = $dir;
    $_SESSION['url'] = $url;

    /**
     * Page class for displaying and handling things
     * @property PDO $pdo A PDO object for working with server.
     */
    class Page{
        protected $url;
        protected $pdo;
        protected $dir;

        public function __construct(PDO $pdo) {
            $this->pdo = $pdo;
            $this->dir = $_SESSION['dir'];
            $this->url = $_SESSION['url'];
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
        protected function redirect($location){
            header("Location: $location");
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

    // remake the architecture so this is automatic
    $pages_names = [
        '/',
        '/profile',
        '/blogpage',
        '/about',
        '/blog',
        '/reg',
        '/auth',
        '/create_post',
        '/pin_post',
        '/comment',
        '/works',
        '/advertisement'
    ];

    if(array_search($url, $pages_names, true) !== false){
        switch ($url){
            case '/':
                include_once 'handlers/main.php';
                break;
            case '/about':
                include_once 'handlers/blogpage.php';
                break;
            default:
                include_once "handlers$url.php";
                break;
        }
    }
    else{
        include_once "view/404.html";
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
    if(isset($cur_page)){
        $cur_page->display();
    }
?>
</body>
</html>