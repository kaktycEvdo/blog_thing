<?php
if(!isset($_POST['name'])){
    include_once 'components/profile_form.php';
}
else{
    require 'checking_module.php';
    // check the data AND do the thing
    $qChangeName = $mysql->prepare('UPDATE users SET name = :name WHERE id = '.$_SESSION['user_id']);
    $qChangeEmail = $mysql->prepare('UPDATE users SET email = :email WHERE id = '.$_SESSION['user_id']);
    $qChangePassword = $mysql->prepare('UPDATE users SET password = :password WHERE id = '.$_SESSION['user_id']);
    $qChangePFP = $mysql->prepare('UPDATE users SET pfp = :pfp WHERE id = '.$_SESSION['user_id']);
    $qChangeBG = $mysql->prepare('UPDATE users SET background = :bg WHERE id = '.$_SESSION['user_id']);

    if(isset($_POST['newPassword']) && isset($_POST['repeatPassword'])){
        if($_POST['newPassword'] != $_POST['repeatPassword']){
            $_SESSION['response'] = [1, 'Ошибка: Пароли не совпадают'];
            header('Location: profile');
            die;
        }
        $qChangePassword->bindParam('password', hash('sha256', $_POST['newPassword']));
        if($qChangePassword->execute()) $_SESSION['response'] = [1, 'Изменения успешны'];
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
            if($qChangeName->execute()) $_SESSION['response'] = [1, 'Изменения успешны'];
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
            if($qChangeEmail->execute()) $_SESSION['response'] = [1, 'Изменения успешны'];
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

        $res = validateImage($img, $to);

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
        $to = str_replace('static/', '', $to);
        $qChangePFP->bindParam('pfp', $to);
        if($qChangePFP->execute()) $_SESSION['response'] = [1, 'Изменения успешны'];
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

        $res = validateImage($img, $to);

        if($res[0] == 1){
            $_SESSION['response'] = $res;
            header('Location: profile');
            die;
        }
        $to = str_replace('static/', '', $to);
        $qChangeBG->bindParam('bg', $to);
        if($qChangeBG->execute()) $_SESSION['response'] = [1, 'Изменения успешны'];
    }

    header("Location: profile");
}