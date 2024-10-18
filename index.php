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
    <?php include_once 'view/components/header.php';
    echo '<main>';
    session_start();
    if(isset($_SESSION['response'])){
        $res = $_SESSION['response'];
        echo '<div class="modal '.($res[0] ? "merror" : "msuccess").'">'.$res[1].'</div>';
        $_SESSION['response'] = null;
    }
    switch (explode('?', $_SERVER['REQUEST_URI'])[0]){
        case '/':
        case '/index':
        case '/main':
            if(isset($_SESSION['left_user_id']) || isset($_GET['left_user_id'])){
                require 'profile_info.php';
                include_once 'view/components/leftpanel.php';
            }
            include_once 'view/main.php';
            break;
        case '/profile':
            $_SESSION['left_user_id'] = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : -1;
            require 'profile_info.php';
            include_once 'view/components/leftpanel.php';
            include_once 'view/profile.php';
            break;
        case '/about':
            if(isset($_SESSION['user_id'])){
                $_SESSION['left_user_id'] = $_SESSION['user_id'];
                require 'profile_info.php';
                include_once 'view/components/leftpanel.php';
                include_once 'view/blogpage.php';
            }
            else{
                header('Location: reg');
            }
            break;
        case '/reg':
            include_once 'view/reg.php';
            break;
        default:
            include_once 'view/404.html';
            break;
    }
    echo '</main>';
    ?>
</body>
</html>