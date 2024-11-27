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
            // $_POST['newPassword'] $_POST['repeatPassword']
            $user->update();
        }
        else{
            include_once 'view/components/profile_form.php';
        }
    }
}
$cur_page = new ProfilePage($pdo, $dir);