<?php
require_once 'utility_functions.php';

/**
 * Blog class for easier printing on page
 * @property int $type Type of the blog. Inserted automatically. 1 - normal, 2 - video promo.
 * ...
 */
class Blog{
    private $pinned;
    protected $blog_id;
    protected $pdo;
    public $author;
    public $content;
    public $header;
    public $media;
    public $tags;
    public $comment_ability;
    public $last_change_date;
    private $type;

    public function __construct(int $id, PDO $pdo) {
        $this->blog_id = $id;
        $this->pdo = $pdo;

        $stmt = $pdo->prepare("SELECT * FROM posts WHERE id = :id");
        $stmt->bindParam('id', $id);
        $stmt->execute();
        $blog = $stmt->fetch(PDO::FETCH_OBJ);

        $stmt = $pdo->prepare("SELECT users.id as id, users.name as name FROM posts, users WHERE author = users.id AND author = $blog->author");
        $stmt->execute();
        $this->author = $stmt->fetch(PDO::FETCH_OBJ);

        $this->media = $blog->media;
        $this->content = $blog->content;
        $this->comment_ability = $blog->comment_ability;
        $this->last_change_date = $blog->last_change_date;
        $this->header = $blog->header;
        $this->tags = $blog->tags;

        $this->type = 1;

        $this->media = "static/user/".$this->author->name."/post_media/$blog->media";

        if(preg_match('/image\//', mime_content_type($this->media))){

        }
    }

    public function printout(){

    }
}

/**
 * A comment object
 * @property array<string> $comment Assoc array of comment values.
 * @property PDO $pdo Current PDO object.
 * @property int $blog_id ID of blog that has the comments.
 */
class Comment{
    private $responses;
    private $blog_id;
    private $id;
    public $text;
    public $publish_date;
    public $author;
    private $pdo;
    
    public function __construct(int $id, int $blog_id, PDO $pdo) {
        $this->pdo = $pdo;

        $stmt = $this->pdo->prepare("SELECT * FROM comment WHERE id = :id AND post = :bid");
        $stmt->bindParam('id', $id);
        $stmt->bindParam('bid', $blog_id);
        $stmt->execute();
        $this->comment = $stmt->fetch();

        $this->text = $this->comment['text'];
        $this->publish_date = $this->comment['publish_date'];

        // comment author should be got by other means than class/session
        $stmt = $this->pdo->prepare('SELECT id, name, pfp FROM users WHERE id = :id');
        $stmt->bindParam('id', $this->comment['author']);
        $stmt->execute();
        $this->author = $stmt->fetch(PDO::FETCH_OBJ);

        $this->responses = $this->pdo->query('SELECT * FROM comment WHERE response = '.$this->comment['id'])->fetchAll();
    }

    /**
     * @return void Displays the comment in formatted manner.
     */
    public function display(){
        $time = $this->comment['publish_date'];
        $datetime1 = date_create($time);
        $datetime2 = date_create('now',new DateTimeZone('Asia/Novosibirsk'));
        $interval = date_diff($datetime1, $datetime2);
        $display_time = formatDisplayTime($interval);

        $pfp = $this->author->pfp != '' ? 'static/user/'.$this->author->name.'/'.$this->author->pfp : 'static/user-default.png';

        echo '<div class="comment">
        <div class="author_data">
        <div class="author_pfp"><img src="'.$pfp.'"></div>
        <div class="author_name">
        <a href="blogpage?user='.$this->author->id.'">'.$this->author->name.'</a>
        <p>'.$display_time.'</p>
        </div>
        </div>
        <div class="comment_data">
        <div class="comment_text">'.$this->comment['text'].'</div>
        </div>
        <a class="accenttext respond" id="r'.$this->comment['id'].'">ответить</a>
        <form id="f'.$this->comment['id'].'" class="respond_form hidden" action="comment?response='.$this->comment['id'].'&blog_id='.$this->blog_id.'" method="POST">
        <input type="text" placeholder="Текст ответа" name="text">
        <div>
        <input type="submit" value="Ответить">
        <button id="c'.$this->comment['id'].'" class="other-button respond_cancel">Отмена</button>
        </div>
        </form>';
    }
}

$blog_id = $_GET['id'];
$blog = new Blog($blog_id, $pdo);

$authorDataQ = $pdo->prepare('SELECT name, pfp FROM users WHERE id = :id');
$authorDataQ->bindParam('id', $blog->author->id);
$authorDataQ->execute();
$author_data = $authorDataQ->fetch();

$date = date('d.m.Y', strtotime($blog->last_change_date));

$file = null;
$file_type = null;
if($blog->media != null){
    $file = 'static/user/'.$author_data['name'].'/post_media/'.$blog->media;
    if(file_exists($file) &&
    preg_match('/image\//', mime_content_type($file))){
        $file_type = 'img';
    }
    else if(file_exists($file) &&
    preg_match('/video\//', mime_content_type($file))){
        $file_type = 'video';
    }
}
?>
<div class="modal" id="share">
    <div class="modal_content">
        <div class="interface">
            <div></div>
            <button class="modal_close">×</button>
        </div>
        <?php include 'view/modal/share.php'; ?>
    </div>
</div>
<div class="post">
    <div class="post_interface">
        <div>
            <a href="javascript:history.back()">вернуться назад</a>
            <a href="pin_post?id=<?php // echo $blog->blog_id ?>">
                <?php // echo $blog->pinned != 1 ? 'закрепить' : 'закреплено'?>
            </a>
        </div>
        <div>
            <a href="#">поделиться</a>
        </div>
        <script>
            let share_button = document.querySelector('.post_interface > div:nth-child(2) > a');
            share_button.addEventListener('click', (e) => {
                e.preventDefault();

                openModal(document.querySelector('#share'));
            });
        </script>
    </div>
    <?php echo isset($blog->header) ? "<h3>$blog->header</h3>" : null ?>
    <div class="extras">
        <div class="date"><?php echo $blog->last_change_date ?></div>
        <?php echo isset($blog->tags) ? "• <div class='tags'>$blog->tags</div>" : null ?>
    </div>
    <?php
    if($file){
        switch($file_type){
            case 'img':
                echo "<img src='$file'>";
                break;
            case 'video':
                echo "<video controls src='$file'></video>";
                break;
        }
    }
    ?>
    <div class="inner">
        <div class="content">
            <?php echo $blog->content ?>
        </div>
    </div>
    <hr>
    <?php
    if($blog->comment_ability == 1){
        $getCommentsQ = $pdo->prepare('SELECT id FROM comment WHERE post = :id');
        $getCommentsQ->bindParam('id', $blog_id);
        $getCommentsQ->execute();

        $comments = $getCommentsQ->fetchAll();

        echo '<div class="comment_section"><h3>Обсуждение</h3><form method="POST" action="comment?blog_id='.$blog_id.'"><input type="text" placeholder="Текст комментария" name="text" id="text"><input type="submit" value="Отправить"></form>';
        if(!$comments || sizeof($comments) == 0){
            echo '<div class="no_comments">Нет комментариев под постом :(</div>';
        }
        else{
            foreach($comments as $comment_data){
                $comment = new Comment($comment_data['id'], $blog_id, $pdo);

                // comment => comment => comment

                $comment->display();
            }
        }
        echo '</div>';
    }
    else{
        echo '<div class="comment_section">
        <div class="no_comments">Под постом нельзя оставлять комментарии</div>
        </div>';
    }
    ?>
    <script>
        let respondlinks = document.getElementsByClassName('respond');
        let cancellinks = document.getElementsByClassName('respond_cancel');
        let forms = document.getElementsByClassName('respond_form');

        function openForm(form_i, link_i){
            // let id = form_i.id.slice(1);

            link_i.classList.add('hidden');
            form_i.classList.remove('hidden');
        }
        function closeForm(form_i, link_i){
            // let id = form_i.id.slice(1);

            link_i.classList.remove('hidden');
            form_i.classList.add('hidden');
        }
        [...respondlinks].forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                let form = document.getElementById('f'+link.id.slice(1));

                openForm(form, link);
            })
        });
        [...cancellinks].foreach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                let form = document.getElementById('f'+link.id.slice(1));

                closeForm(form, link);
            })
        })
    </script>
</div>