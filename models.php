<?php
/**
 * Generic class for models.
 * Model – an abstract construct for working with object in PDO-connected tables.
 */
class Model{

    protected function changeField(PDO $pdo, $field, $content){
        $modal = new ServerModal;
        $q = $pdo->prepare("UPDATE users SET $field = :content WHERE id = $this->id");
        $q->bindParam('content', $content);
        return $q->execute();
    }
}
/**
 * A user model.
 * @property string $email Email. Under 100 characters.
 * @property string $password Password. Keep empty if not needed.
 * @property string $name Username. Under 150 characters.
 */
class User extends Model{
    public $id;
    public $name;
    private $email;
    private $password;
    public $description;
    public $brief;
    public $pfp;
    public $background;

    public function __construct(string $username, string $email, string $password, PDO $pdo, string $pfp = null,
    string $background = null, string $description = null, string $brief = null, int $id = null) {
        if($id == null){
            $new_id = $pdo->prepare("SELECT id FROM users ORDER BY id DESC")->fetch(PDO::FETCH_NUM)+1;

            $this->id = $new_id;
        }
        else{
            $this->id = $id;
        }
        $this->name = $username;
        $this->email = $email;
        $this->password = $password;
        $this->background = $background;
        $this->description = $description;
        $this->brief = $brief;
        $this->pfp = $pfp;
    }

    public function initiateUser() {
        $_SESSION['user'] = serialize($this);
    }

    public function getInfo(): array{
        return [
            'name' => $this->name,
            'email' => $this->email,
            'pfp' => $this->pfp,
            'brief' => $this->brief,
            'description' => $this->description,
            'background' => $this->background
        ];
    }
    
    public function authorize(PDO $pdo) {
        $query = $pdo->prepare('SELECT id, name, email, description, brief, pfp, background FROM users WHERE name = :name and password = :password');
        $queryE = $pdo->prepare('SELECT id, name, email, description, brief, pfp, background FROM users WHERE email = :name and password = :password');
        $data = null;

        if (str_contains($this->name, '@')){
            $this->email = $this->name;
            $queryE->bindParam('name', $this->name);
            $queryE->bindParam('password', $this->password);
            $queryE->execute();

            $data = $query->fetch();
        }
        else{
            $query->bindParam('name', $this->name);
            $query->bindParam('password', $this->password);
            $query->execute();

            $data = $query->fetch();
        }

        $modal = new ServerModal;

        if($data){
            $this->name = $data['name'];
            $this->email = $data['email'];
            $this->password = $data['password'];
            $this->pfp = $data['pfp'];
            $this->background = $data['background'];
            $this->brief = $data['brief'];
            $this->description = $data['description'];
            $this->id = $data['id'];

            $_SESSION['user'] = serialize($this);
            $_SESSION['left_user'] = serialize($this);
        }
        else{
            $this->name = null;
            $this->email = null;
            $this->password = null;
            $modal->throwModal('Ошибка авторизации: неверные данные', true, 'auth');
            die;
        }

        $modal->throwModal('Авторизация прошла успешно', false, '');
    }
    public function register(PDO $pdo){
        // do the thing

        $query = $pdo->prepare('INSERT INTO users(name, email, password, pfp) VALUES (:name, :email, :password, :pfp)');
        $query->bindValue('name', $this->name);
        $query->bindValue('email', $this->email);
        $query->bindValue('password', $this->password);
        $query->bindValue('pfp', $this->pfp);

        if(!$query->execute()){
            echo 'Ошибка создания пользователя';
            die;
        }

        $id = $pdo->query('SELECT id FROM users ORDER BY id DESC LIMIT 1')->fetch(PDO::FETCH_COLUMN);

        $_SESSION['user_id'] = $id;
        $_SESSION['left_user_id'] = $id;
        header('Location: profile');
    }

    public function saveChanges(PDO $pdo){
        $modal = new ServerModal();
        
        $stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password, description = :description, brief = :brief, pfp = :pfp, background = :background WHERE id=$this->id");
        $stmt->bindParam('name', $this->name);
        $stmt->bindParam('email', $this->email);
        $stmt->bindParam('password', $this->password);
        $stmt->bindParam('description', $this->description);
        $stmt->bindParam('brief', $this->brief);
        $stmt->bindParam('pfp', $this->pfp);
        $stmt->bindParam('background', $this->background);
        $res = $stmt->execute();
        
        if($res == false) $modal->throwModal('Ошибка обновления пользователя в сервере', true, 'profile');
        else $modal->throwModal('Обновление пользователя успешно', false);
        header("Location: ../blog-project");
    }
    public function updatePFP($img){
        $modal = new ServerModal();

        if($img['name'] != ''){
            $to = '';

            if($img == 'default' || $img['name'] == 'user-default.png'){
                $to = 'static/user-default.png';
            } else {
                $to = "static/user/$this->name/".$img['name'];
            }

            if(!is_dir("static/user/$this->name")){
                mkdir("static/user/$this->name");
            }

            $val = validateMedia($img, $to);
            if($val[0] == 1) $modal->throwModal($val[1], true, 'profile');
            $this->pfp = $img['name'];
        }
    }

    public function updateBackground($img){
        $modal = new ServerModal();

        if($img['name'] != ''){
            $to = "static/user/$this->name/".$img['name'];

            if(!is_dir("static/user/$this->name")){
                mkdir("static/user/$this->name");
            }

            $val = validateMedia($img, $to);
            if($val[0] == 1) $modal->throwModal($val[1], true, 'profile');
            $this->background = $img['name'];
        }
    }

    public function updatePassword(string $newPassword, string $repeatPassword){
        $modal = new ServerModal();

        $hashed_pswrd = hash('sha256', $newPassword);
        if($newPassword != null && $repeatPassword != null && $hashed_pswrd != $this->password){
            $val = validateChangingPassword($newPassword, $repeatPassword);
            if($val[0] == 1) $modal->throwModal($val[1], true, 'profile');
            $this->password = hash('sha256', $newPassword);
        }
    }

    public function updateName(string $name){
        $modal = new ServerModal();

        if($name != $this->name){
            $val = validateName($name);
            if($val[0] == 1) $modal->throwModal($val[1], true, 'profile');
            rename("static/user/$this->name", "static/user/$name");
            $this->name = $name;
        }
    }

    public function updateEmail(string $email){
        $modal = new ServerModal();

        if($email != $this->email){
            $val = validateEmail($email);
            if($val[0] == 1) $modal->throwModal($val[1], true, 'profile');
            $this->email = $email;
        }
    }

    public function updateDescription(string $description){
        if($description != $this->description){
            $this->description = $description;
        }
    }

    public function updateBrief(string $brief){
        if($brief != $this->brief){
            $this->brief = $brief;
        }
    }
}