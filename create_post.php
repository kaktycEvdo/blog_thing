<?php
if(isset($_POST['type']) && $user_data){
    require_once 'checking_module.php';
    switch($_POST['type']){
        case 1:{
            $query = $mysql->query('SELECT id FROM posts WHERE author = '.$_SESSION['user_id'].' ORDER BY id DESC', PDO::FETCH_COLUMN, 0);
            $createQ = $mysql->prepare('INSERT INTO posts (author, content, media, tags, comment_ability, type) VALUES ('.$_SESSION['user_id'].', :content, :cover, :tags, :commentary, 1)');

            $id = $query->fetch();
            if(is_null($id) || $id == ''){
                $id = 0;
            }
            if(!isset($_POST['content'])){
                $_SESSION['response'] = [1, 'Ошибка: пустой пост'];
                header('Location: about');
                die;
            }
            $content = $_POST['content'];
            $createQ->bindParam('content', $content);
            if(isset($_FILES['cover']) && $_FILES['cover']['name'] != ''){
                $img = $_FILES['cover'];
                $profile_name = $user_data['name'];
                $postcovername = 'post'.$id.'cover'.$img['name'];
                $to = 'static/user/'.$profile_name.'/covers/'.$postcovername;
                if(!is_dir('static/user/'.$profile_name.'/covers/')){
                    mkdir('static/user/'.$profile_name.'/covers/');
                }

                $imgVal = validateImage($img, $to);
                if($imgVal[0] == 1){
                    $_SESSION['response'] = $imgVal;
                    header('Location: about');
                    die;
                }
                $createQ->bindParam('cover', $postcovername);
            }
            else{
                $null = null;
                $createQ->bindParam('cover', $null);
            }
            if(isset($_POST['commentary'])){
                $commentary = $_POST['commentary'] == 'on' ? 1 : 0;
                $createQ->bindParam('commentary', $commentary);
            }
            else{
                $false = false;
                $createQ->bindParam('commentary', $false);
            }

            isset($_POST['tags_t'])
            ? $createQ->bindParam('tags', $_POST['tags_t'])
            : $createQ->bindParam('tags', null);

            $res = $createQ->execute();

            if(!$res){
                $_SESSION['response'] = [1, 'Ошибка создания поста'];
                header('Location: about');
                die;
            }
            else{
                $_SESSION['response'] = [0, 'Пост успешно создан'];
                header('Location: about');
            }
            break;
        }
        case 2:{
            $query = $mysql->query('SELECT id FROM posts WHERE author = '.$_SESSION['user_id'].' ORDER BY id DESC', PDO::FETCH_COLUMN, 0);
            $createQ = $mysql->prepare('INSERT INTO posts (author, content, media, tags, comment_ability, type) VALUES ('.$_SESSION['user_id'].', :header, :link, :tags, :commentary, 2)');

            $id = $query->fetch();
            if(is_null($id) || $id == ''){
                $id = 0;
            }
            if(!isset($_POST['header'])){
                $createQ->bindParam('header', null);
            }
            else{
                if(strlen($_POST['header']) > 500){
                    $_SESSION['response'] = [1, 'Ошибка: слишком длинный заголовок видео'];
                    header('Location: about');
                    die;
                }
                $header = $_POST['header'];
                $createQ->bindParam('header', $header);
            }
            if(isset($_FILES['video'])){
                $video = $_FILES['video'];
                $profile_name = $user_data['name'];
                $postvideoname = 'post'.$id.'video'.$video['name'];
                $to = 'static/user/'.$profile_name.'/videos/'.$postvideoname;
                if(!is_dir('static/user/'.$profile_name.'/videos/')){
                    mkdir('static/user/'.$profile_name.'/videos/');
                }

                if (is_uploaded_file($video['tmp_name'])) {
                    if (!move_uploaded_file($video['tmp_name'], $to)) {
                        $_SESSION['response'] = [1, 'Ошибка: Невозможно переместить файл в необходимый каталог'];
                        header('Location: about');
                        die;
                    }
                } else {
                    $_SESSION['response'] = [1, 'Ошибка: Возможна атака через загрузку файла'];
                    header('Location: about');
                    die;
                }

                $createQ->bindParam('link', $postvideoname);
            }
            else{
                $_SESSION['response'] = [1, 'Ошибка: нет видео'];
                header('Location: about');
            }
            if(isset($_POST['commentary'])){
                $commentary = $_POST['commentary'] == 'on' ? 1 : 0;
                $createQ->bindParam('commentary', $commentary);
            }
            else{
                $createQ->bindParam('commentary', false);
            }

            isset($_POST['tags_v'])
            ? $createQ->bindParam('tags', $_POST['tags_v'])
            : $createQ->bindParam('tags', null);

            $res = $createQ->execute();

            if(!$res){
                $_SESSION['response'] = [1, 'Ошибка создания поста'];
                header('Location: about');
                die;
            }
            else{
                $_SESSION['response'] = [0, 'Пост успешно создан'];
                header('Location: about');
            }
            break;
        }
        case 3:{
            $query = $mysql->query('SELECT id FROM stories WHERE author = '.$_SESSION['user_id'].' ORDER BY id DESC', PDO::FETCH_COLUMN, 0);
            $createQ = $mysql->prepare('INSERT INTO stories (author, name, media, comment_ability) VALUES ('.$_SESSION['user_id'].', :description, :media, :commentary)');

            $id = $query->fetch();
            if(is_null($id) || $id == ''){
                $id = 0;
            }
            if(!isset($_POST['description'])){
                $createQ->bindParam('description', null);
            }
            else{
                $description = $_POST['description'];
                if(strlen($description) > 150){
                    $_SESSION['response'] = [1, 'Ошибка: слишком длинный заголовок сторис'];
                    header('Location: about');
                    die;
                }
                $createQ->bindParam('description', $description);
            }
            if(isset($_FILES['story'])){
                $story = $_FILES['story'];
                $profile_name = $user_data['name'];
                $storyvideoname = 'story'.$id.'media'.$story['name'];
                $to = 'static/user/'.$profile_name.'/stories/'.$storyvideoname;
                if(!is_dir('static/user/'.$profile_name.'/stories/')){
                    mkdir('static/user/'.$profile_name.'/stories/');
                }

                if (is_uploaded_file($story['tmp_name'])) {
                    if (!move_uploaded_file($story['tmp_name'], $to)) {
                        $_SESSION['response'] = [1, 'Ошибка: Невозможно переместить файл в необходимый каталог'];
                        header('about');
                        die;
                    }
                } else {
                    $_SESSION['response'] = [1, 'Ошибка: Возможна атака через загрузку файла'];
                    header('about');
                    die;
                }

                $createQ->bindParam('media', $storyvideoname);
            }
            else{
                $_SESSION['response'] = [1, 'Ошибка: нет видео/изображения'];
                header('Location: about');
            }
            if(isset($_POST['commentary'])){
                $commentary = $_POST['commentary'] == 'on' ? 1 : 0;
                $createQ->bindParam('commentary', $commentary);
            }
            else{
                $createQ->bindParam('commentary', false);
            }

            $res = $createQ->execute();

            if(!$res){
                $_SESSION['response'] = [1, 'Ошибка создания поста'];
                header('Location: about');
                die;
            }
            else{
                $_SESSION['response'] = [0, 'Пост успешно создан'];
                header('Location: about');
            }
            break;
        }
    }
}
else{
    $_SESSION['response'] = [1, 'Ошибка: пустой пост или неавторизованный пользователь'];
    header('Location: ../'.$dir);
}