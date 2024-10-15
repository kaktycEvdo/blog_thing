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
    if(isset($_SESSION['response'])){
        $res = $_SESSION['response'];
        echo '<div class="modal '.($res[0] ? "merror" : "msuccess").'">'.$res[1].'</div>';
        $_SESSION['response'] = null;
    }
    switch ($_SERVER['REQUEST_URI']){
        case '/':
        case 'index':
        case 'main':
            include_once 'view/main.php';
            break;
        case '/profile':
            require 'connect_to_db.php';
            require 'profile_info.php';
            include_once 'view/profile.php';
            break;
        default:
            include_once 'view/404.html';
            break;
    }
    echo '</main>';
    ?>
</body>
</html>