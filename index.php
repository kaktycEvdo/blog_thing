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
    <?php
    echo '<main>';
    echo '<section>';
    include_once 'view/components/header.php';
    session_start();
    if(isset($_SESSION['response'])){
        $res = $_SESSION['response'];
        echo '<div class="modal '.($res[0] ? "merror" : "msuccess").'">'.$res[1].'</div>';
        $_SESSION['response'] = null;
    }

    require 'connect_to_db.php';
    require 'profile_info.php';
    
    $url = isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : explode('?', $_SERVER['REQUEST_URI'])[0];
    $url = str_replace('/sec-project/', '',  $url);

    switch ($uri){
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
        case '/reg':
            include_once 'view/reg.php';
            break;
        case '/auth':
            include_once 'view/auth.php';
            break;
        case '/create_post':
            include_once 'create_post.php';
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