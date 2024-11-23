<?php
class ProfilePage extends Page{
    function displayContent(){
        $pdo = $this->pdo;
        $url = $this->url;
        
        $user = unserialize($_SESSION['user']);
        $user_data = $user->getInfo();
        include_once 'view/components/profile_form.php';
    }
}
$cur_page = new ProfilePage($pdo, $dir);

if(isset($_POST['name'])){
    require 'checking_module.php';
    $modal = new ServerModal;
    $user = unserialize($_SESSION['user']);

    if(isset($_POST['newPassword']) && isset($_POST['repeatPassword'])){
        if($_POST['newPassword'] != $_POST['repeatPassword']){
            $modal->throwModal('Ошибка: Пароли не совпадают', true, 'profile');
            die;
        }
        if($_POST['newPassword'] != ''){
            $modal->throwModal('Ошибка: Пароль не может быть пустой строкой', true, 'profile');
            die;
        }
        $password = hash('sha256', $_POST['newPassword']);
        $user->changePassword($password);
    }

    if(isset($_POST['description']) && $_POST['description'] != $user_data['description']){
        // update description
        $description = $_POST['description'];
        $qChangeDescription->bindParam('desc', $description);
        if($qChangeDescription->execute()) $_SESSION['response'] = [0, 'Изменения успешны'];
    }
    
    if(isset($_POST['brief']) && $_POST['brief'] != $user_data['brief']){
        // update brief
        $brief = $_POST['brief'];
        $qChangeBrief->bindParam('brief', $brief);
        if($qChangeBrief->execute()) $_SESSION['response'] = [0, 'Изменения успешны'];
    }

    if(isset($_POST['name']) && isset($_POST['email'])){
        if($_POST['name'] != $user_data['name']){
            // update username
            $name = $_POST['name'];
            $res = validateName($name);
            if($res[0] == 1){
                $_SESSION['response'] = $res;
                header("Location: profile");
                die;
            }
            $qChangeName->bindParam('name', $name);
            if($qChangeName->execute()) {
                if(rename('static/user/'.$user_data['name'].'/', 'static/user/'.$name.'/')) {
                    $_SESSION['response'] = [0, 'Изменения успешны'];
                }
            };
        }
        if($_POST['email'] != $user_data['email']){
            // update email
            $email = $_POST['email'];
            $res = validateEmail($email);
            if($res[0] == 1){
                $_SESSION['response'] = $res;
                header("Location: profile");
                die;
            }
            $qChangeEmail->bindParam('email', $email);
            if($qChangeEmail->execute()) $_SESSION['response'] = [0, 'Изменения успешны'];
        }
    }

    if(isset($_FILES['profile_image']) && $_FILES['profile_image']['name'] != ''){
        $img = $_FILES['profile_image'];
        $to = '';

        if(!isset($img) || $img == 'default'){
            $to = 'static/user-default.png';
        } else {
            $to = 'static/user/'.$user_data['name'].'/'.$img['name'];
        }

        if(!is_dir('static/user/'.$user_data['name'])){
            mkdir('static/user/'.$user_data['name']);
        }

        $res = validateMedia($img, $to);

        if($res[0] == 1){
            $_SESSION['response'] = $res;
            header('Location: profile');
            die;
        }
        $name = $_POST['name'];
        $res = validateName($name);
        if($res[0] == 1){
            $_SESSION['response'] = $res;
            header("Location: profile");
            die;
        }
        $to = str_replace('static/'.$user_data['name'].'/', '', $to);
        $qChangePFP->bindParam('pfp', $to);
        if($qChangePFP->execute()) $_SESSION['response'] = [0, 'Изменения успешны'];
    }
    if(isset($_FILES['profile_bg']) && $_FILES['profile_bg']['name'] != ''){
        $to = '';
        $img = $_FILES['profile_bg'];

        if(!isset($img) || $img == 'default'){
            $to = 'static/bg-default.png';
        } else {
            $to = 'static/user/'.$user_data['name'].'/'.$img['name'];
        }

        if(!is_dir('static/user/'.$user_data['name'])){
            mkdir('static/user/'.$user_data['name']);
        }

        $res = validateMedia($img, $to);

        if($res[0] == 1){
            $_SESSION['response'] = $res;
            header('Location: profile');
            die;
        }
        $to = str_replace('static/'.$user_data['name'].'/', '', $to);
        $qChangeBG->bindParam('bg', $to);
        if($qChangeBG->execute()) $_SESSION['response'] = [0, 'Изменения успешны'];
        else $_SESSION['response'] = [1, 'Изменение не вышло'];
    }

    header("Location: profile");
}