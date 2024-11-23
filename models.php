<?php
/**
 * Generic class for models.
 * Model – an abstract construct for working with object in PDO-connected tables.
 */
class Model{
}
/**
 * A user model.
 * @property string $email Email. Under 100 characters.
 * @property string $password Password. Hashes.
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

        // $qChangeName = $this->pdo->prepare("UPDATE users SET name = :name WHERE id = $this->user_id");
        // $qChangeEmail = $this->pdo->prepare("UPDATE users SET email = :email WHERE id = $this->user_id");
        // $qChangePFP = $this->pdo->prepare("UPDATE users SET pfp = :pfp WHERE id = $this->user_id");
        // $qChangeBG = $this->pdo->prepare("UPDATE users SET background = :bg WHERE id = $this->user_id");
        // $qChangeBrief = $this->pdo->prepare("UPDATE users SET brief = :brief WHERE id = $this->user_id");
        // $qChangeDescription = $this->pdo->prepare("UPDATE users SET description = :desc WHERE id = $this->user_id");
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

        $modal->throwModal('Авторизация прошла успешно', false, 'index');
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
    public function changeField(PDO $pdo, $field, $content){
        $modal = new ServerModal;
        $qChangePassword = $pdo->prepare("UPDATE users SET $field = :password WHERE id = $this->user_id");
        $qChangePassword->bindParam('password', $password);
        if($qChangePassword->execute()) $_SESSION['response'] = [0, 'Изменения успешны'];
    }
}