<?php
class WorksPage extends Page{
    public function displayContent(){
        $pdo = $this->pdo;
        $stmt = $pdo->prepare("SELECT * FROM works WHERE author = :id");
        if(!isset($_GET['user']) || $_GET['user'] == null && !isset($_SESSION['user']) || $_SESSION['user'] == null){
            $this->redirect('auth');
        }
        $uid = isset($_GET['user']) && $_GET['user'] != null ? $_GET['user'] : unserialize($_SESSION['user'])->id;
        $stmt->bindParam('id', $user_id);
        $stmt->execute();
        $works = $stmt->fetchAll();
        $suid = unserialize($_SESSION['user'])->id;

        require_once 'view/works.php';
    }
}
$cur_page = new WorksPage($pdo);