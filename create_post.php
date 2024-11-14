<?php
// check if it works later
if(isset($_POST['type']) && $user_data){
    function checkHeader($createQ): bool{
        if(!isset($_POST['header'])){
            $createQ->bindParam('header', null);
            return 1;
        }
        else{
            if(strlen($_POST['header']) > 500){
                $_SESSION['response'] = [1, 'Ошибка: слишком длинный заголовок видео'];
                header('Location: about');
                die;
            }
            $header = $_POST['header'];
            $createQ->bindParam('header', $header);
            return 1;
        }
    }
    function checkDescription($createQ): bool{
        if(!isset($_POST['description'])){
            $createQ->bindParam('description', null);
            return 1;
        }
        else{
            $description = $_POST['description'];
            if(strlen($description) > 150){
                $_SESSION['response'] = [1, 'Ошибка: слишком длинный заголовок сторис'];
                header('Location: about');
                die;
            }
            $createQ->bindParam('description', $description);
            return 1;
        }
    }
    function checkStory($createQ, $user_data, $id): bool{
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
            return 1;
        }
        else{
            $_SESSION['response'] = [1, 'Ошибка: нет видео/изображения'];
            header('Location: about');
            return 0;
        }
    }
    function checkTags($createQ, $tags): bool{
        isset($tags)
            ? $createQ->bindParam('tags', $tags)
            : $createQ->bindParam('tags', null);
        return 1;
    }
    function checkContent($createQ, $isVideo): bool{
        if($isVideo){
            $createQ->bindParam('content', null);
            return 1;
        }
        if(!isset($_POST['content'])){
            $_SESSION['response'] = [1, 'Ошибка: пустой пост'];
            header('Location: about');
            die;
        }
        $content = $_POST['content'];
        $createQ->bindParam('content', $content);
        return 1;
    }
    function checkCommentary($createQ): bool{
        if(isset($_POST['commentary'])){
            $commentary = $_POST['commentary'] == 'on' ? 1 : 0;
            $createQ->bindParam('commentary', $commentary);
        }
        else{
            $createQ->bindParam('commentary', 0);
        }
        return 1;
    }
    function checkMedia($createQ, $user_data, $id, $media){
        if(isset($media) && $media['name'] != ''){
            $profile_name = $user_data['name'];
            $postmedianame = 'post'.$id.'cover'.$media['name'];
            $to = 'static/user/'.$profile_name.'/post_media/'.$postmedianame;
            if(!is_dir('static/user/'.$profile_name.'/post_media/')){
                mkdir('static/user/'.$profile_name.'/post_media/');
            }

            $imgVal = validateMedia($media, $to);
            if($imgVal[0] == 1){
                $_SESSION['response'] = $imgVal;
                header('Location: about');
                die;
            }
            $createQ->bindParam('media', $postmedianame);
        }
        else{
            $null = null;
            $createQ->bindParam('media', $null);
        }
    }
    function checkBothExist(){
        if(!isset($_POST['content']) && !isset($_POST['header'])){
            $_SESSION['reponse'] = [1, 'Ошибка: нет ни названия, ни описания поста'];
            header('Location: about');
            die;
        }
    }

    require_once 'checking_module.php';
    switch($_POST['type']){
        case 1:{
            $query = $mysql->query('SELECT id FROM posts WHERE author = '.$_SESSION['user_id'].' ORDER BY id DESC', PDO::FETCH_COLUMN, 0);
            $createQ = $mysql->prepare('INSERT INTO posts (author, content, media, tags, comment_ability, type, header) VALUES ('.$_SESSION['user_id'].', :content, :media, :tags, :commentary, 1, :header)');

            $id = $query->fetch();
            if(is_null($id) || $id == ''){
                $id = 0;
            }
            
            checkBothExist();
            checkContent($createQ, false);
            checkHeader($createQ);
            checkCommentary($createQ);
            checkTags($createQ, $_POST['tags_t']);
            checkMedia($createQ, $user_data, $id, $_POST['cover']);

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
            $createQ = $mysql->prepare('INSERT INTO posts (author, content, media, tags, comment_ability, type) VALUES ('.$_SESSION['user_id'].', :header, :media, :tags, :commentary, 2)');

            $id = $query->fetch();
            if(is_null($id) || $id == ''){
                $id = 0;
            }

            checkBothExist();
            checkContent($createQ, true);
            checkHeader($createQ);
            checkCommentary($createQ);
            checkTags($createQ, $_POST['tags_v']);
            checkMedia($createQ, $user_data, $id, $_POST['video']);

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
            checkDescription($createQ);
            checkStory($createQ, $user_data, $id);
            checkCommentary($createQ);

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