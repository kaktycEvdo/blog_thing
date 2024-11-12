<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>БлогPost</title>
    <meta name="description" content="Бесплатный сайт по ведению собственных блогов. Блог – определенный вид текстового и видео-контента, который позволяет делиться впечатлениями из собственной жизни.">
    <link rel="stylesheet" href="static/main.css">
</head>
<body>
<script src="static/main.js"></script>
    <?php
    $dir = '/blog-project';
    echo '<main>';
    echo '<section>';
    session_start();
    require 'connect_to_db.php';
    require_once 'profile_info.php';

    include_once 'view/components/header.php';
    if(isset($_SESSION['response'])){
        $res = $_SESSION['response'];
        echo '<div class="modal '.($res[0] ? "merror" : "msuccess").'">'.$res[1].'</div>';
        $_SESSION['response'] = null;
    }
    
    $url = explode('?', $_SERVER['REQUEST_URI'])[0];
    $url = str_replace($dir, '',  $url);

    switch ($url){
        case '/':
        case '/index':
        case '/main':
            include_once 'view/main.php';
            break;
        case '/profile':
            if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){
                include_once 'view/profile.php';
                $_SESSION['left_user_id'] = $_SESSION['user_id'];
            } else {
                header('Location: auth');
            }
            break;
        case '/about':
            if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != ''){
                include_once 'view/blogpage.php';
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
                    include_once 'view/blogpage.php';
                }
            }
            else{
                header('Location: ../'.$dir);
            }
            break;
        case '/reg':
            include_once 'view/reg.php';
            break;
        case '/auth':
            include_once 'view/auth.php';
            break;
        case '/create_post':
            include_once 'create_post.php';
            break;
        case '/pin_post':
            include_once 'pin_post.php';
            break;
        default:
            include_once 'view/404.html';
            break;
    }
    echo '</section>';
    include_once 'view/components/leftpanel.php';
    echo '</main>';
    ?>
    </body>
</html>