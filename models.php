<?php
/**
 * Generic class for models.
 * Model – an abstract construct for working with object in PDO-connected tables.
 */
class Model{
    protected $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }
}
class User extends Model{
    private $qChangeName;
    private $qChangePFP;
    public $user_id;

    private function __construct() {
        $this->user_id = null;

        $qChangeName = $this->pdo->prepare("UPDATE users SET name = :name WHERE id = $this->user_id");
        $qChangeEmail = $this->pdo->prepare("UPDATE users SET email = :email WHERE id = $this->user_id");
        $qChangePassword = $this->pdo->prepare("UPDATE users SET password = :password WHERE id = $this->user_id");
        $qChangePFP = $this->pdo->prepare("UPDATE users SET pfp = :pfp WHERE id = $this->user_id");
        $qChangeBG = $this->pdo->prepare("UPDATE users SET background = :bg WHERE id = $this->user_id");
        $qChangeBrief = $this->pdo->prepare("UPDATE users SET brief = :brief WHERE id = $this->user_id");
        $qChangeDescription = $this->pdo->prepare("UPDATE users SET description = :desc WHERE id = $this->user_id");
    }

    public function initiateUser() {
        if(isset($_SESSION['user']) && !is_null($_SESSION['user'])){
            $user = unserialize($_SESSION['user']);
            if($user->user_id != null){
                $this->user_id = $user->user_id;
            }
        }
    }
    
    public function authorize(string $name, string $password) {
        $query = $this->pdo->prepare('SELECT id FROM users WHERE name = :name and password = :password');
        $queryEmail = $this->pdo->prepare('SELECT id FROM users WHERE email = :name and password = :password');

        if (str_contains($name, '@')){
            $queryEmail->bindParam('name', $name);
            $queryEmail->bindParam('password', $password);
            $queryEmail->execute();
        }
        else{
            $query->bindParam('name', $name);
            $query->bindParam('password', $password);
            $query->execute();
        }

        $idwn = $query->fetch(PDO::FETCH_COLUMN);
        $idwe = $queryEmail->fetch(PDO::FETCH_COLUMN);

        // change to serializing user to session
        if($idwn){
            $_SESSION['user_id'] = $idwn;
            $_SESSION['left_user_id'] = $idwn;
        }
        else if ($idwe){
            $_SESSION['user_id'] = $idwe;
            $_SESSION['left_user_id'] = $idwe;
        }
        else{
            $modal->changeModal('Ошибка авторизации: неверные данные', true);
            $modal->throwModal('auth');
            die;
        }

        $modal->changeModal('Авторизация прошла успешно');
        $modal->throwModal('auth');
    }
}