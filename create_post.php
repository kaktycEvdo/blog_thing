<?php
if(isset($_POST['type']) && $user_data){
    require_once 'checking_module.php';
    switch($_POST['type']){
        case 1:{
            $query = $mysql->query('SELECT id FROM blogs WHERE author = '.$_SESSION['user_id'].' ORDER BY id DESC', PDO::FETCH_COLUMN, 0);
            $createQ = $mysql->prepare('INSERT INTO blogs (author, content, cover, tags, commentary_ability) VALUES ('.$_SESSION['user_id'].', :content, :cover, :tags, :commentary)');

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
            if(isset($_FILES['cover'])){
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
                $createQ->bindParam('cover', null);
            }
            if(isset($_POST['commentary'])){
                $commentary = $_POST['commentary'] == 'on' ? 1 : 0;
                $createQ->bindParam('commentary', $commentary);
            }
            else{
                $createQ->bindParam('commentary', false);
            }

            isset($_POST['tags'])
            ? $createQ->bindParam('tags', $_POST['tags'])
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
            $query = $mysql->query('SELECT id FROM videos WHERE author = '.$_SESSION['user_id'].' ORDER BY id DESC', PDO::FETCH_COLUMN, 0);
            $createQ = $mysql->prepare('INSERT INTO videos (author, header, link, tags, commentary_ability) VALUES ('.$_SESSION['user_id'].', :header, :link, :tags, :commentary)');

            $id = $query->fetch();
            if(is_null($id) || $id == ''){
                $id = 0;
            }
            if(!isset($_POST['header'])){
                $createQ->bindParam('header', null);
            }
            else{
                $header = $_POST['header'];
                $createQ->bindParam('header', $header);
            }
            if(isset($_FILES['video'])){
                $video = $_FILES['cover'];
                $profile_name = $user_data['name'];
                $postvideoname = 'post'.$id.'video'.$video['name'];
                $to = 'static/user/'.$profile_name.'/videos/'.$postvideoname;
                if(!is_dir('static/user/'.$profile_name.'/videos/')){
                    mkdir('static/user/'.$profile_name.'/videos/');
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

            isset($_POST['tags'])
            ? $createQ->bindParam('tags', $_POST['tags'])
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
    }
}
else{
    $_SESSION['response'] = [1, 'Ошибка: пустой пост или неавторизованный пользователь'];
    header('Location: ../');
}