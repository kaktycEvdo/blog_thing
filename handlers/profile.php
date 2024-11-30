<?php
class ProfilePage extends Page{
    function displayContent(){
        $pdo = $this->pdo;
        $url = $this->url;
        
        if(!isset($_SESSION['user']) || $_SESSION['user'] == null){
            $this->redirect('auth');
        }
        $user = unserialize($_SESSION['user']);
        $user_data = $user->getInfo();
        if(sizeof($_POST)){
            require 'checking_module.php';

            foreach ($_FILES as $key => $array) {
                if($array['name'] == null) continue;
                switch ($key) {
                    case 'profile_image':
                        $user->updatePFP($array);
                        break;
                    case 'profile_bg':
                        $user->updateBackground($array);
                        break;
                }
            }
            foreach ($_POST as $key => $value) {
                if($value == null) continue;
                switch ($key) {
                    case 'name':
                        $user->updateName($value);
                        break;
                    case 'newPassword':
                    case 'repeatPassword':
                        $user->updatePassword($value);
                        break;
                    case 'email':
                        $user->updateEmail($value);
                        break;
                    case 'brief':
                        $user->updateBrief($value);
                        break;
                    case 'description':
                        $user->updateDescription($value);
                        break;
                }
            }
            $user->saveChanges($pdo);
            $_SESSION['left_user'] = serialize($user);
        }
        else{
            include_once 'view/components/profile_form.php';
        }
    }
}
$cur_page = new ProfilePage($pdo, $dir);