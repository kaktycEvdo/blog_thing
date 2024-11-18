<?php
if(!isset($_GET['id'])){
    header('Location: ../'.$dir);
}
else{
    require_once 'utility_functions.php';

    $blog_id = $_GET['id'];
    $blogQ = $mysql->prepare('SELECT * FROM posts WHERE id = :id');
    $blogQ->bindParam('id', $blog_id);

    $blogQ->execute();
    $blog = $blogQ->fetch();

    $authorDataQ = $mysql->prepare('SELECT name, pfp FROM users WHERE id = :id');
    $authorDataQ->bindParam('id', $blog['author']);
    $authorDataQ->execute();
    $author_data = $authorDataQ->fetch();

    $_SESSION['left_user_id'] = $blog['author'];

    $blog['last_change_date'] = date('d.m.Y', strtotime($blog['last_change_date']));

    $file = null;
    $file_type = null;
    if(isset($blog['media'])){
        $file = 'static/user/'.$author_data['name'].'/post_media/'.$blog['media'];
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
                    <a href="pin_post?id=<?php echo $blog['id']?>">
                        <?php echo $blog['pinned'] != 1 ? 'закрепить' : 'закреплено'?>
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
            <?php echo isset($blog['header']) ? "<h3>".$blog['header']."</h3>" : null ?>
            <div class="extras">
                <div class="date"><?php echo $blog['last_change_date'] ?></div>
                <?php echo isset($blog['tags']) ? "• <div class='tags'>".$blog['tags']."</div>" : null ?>
            </div>
            <?php
            if($file){
                switch($file_type){
                    case 'img':
                        echo '<img src="$file">';
                        break;
                    case 'video':
                        echo '<video controls src="'.$file.'"></video>';
                        break;
                }
            }
            ?>
            <div class="inner">
                <div class="content">
                    <?php echo $blog['content'] ?>
                </div>
            </div>
            <hr>
            <?php
            if($blog['comment_ability'] == 1){
                $getCommentsQ = $mysql->prepare('SELECT * FROM comment WHERE post = :id and response IS NULL');
                $getCommentsQ->bindParam('id', $blog_id);
                $getCommentsQ->execute();

                $comments = $getCommentsQ->fetchAll();

                echo '<div class="comment_section"><h3>Обсуждение</h3><form method="POST" action="comment?blog_id='.$blog['id'].'"><input type="text" placeholder="Текст комментария" name="text" id="text"><input type="submit" value="Отправить"></form>';
                if(!$comments || sizeof($comments) == 0){
                    echo '<div class="no_comments">Нет комментариев под постом :(</div>';
                }
                else{
                    foreach($comments as $comment){
                        $stmt = $mysql->prepare('SELECT id, name, pfp FROM users WHERE id = :id');
                        $stmt->bindParam('id', $comment['author']);
                        $stmt->execute();
                        $author = $stmt->fetch();

                        $time = $comment['publish_date'];
                        $datetime1 = date_create($time);
                        $datetime2 = date_create('now',new DateTimeZone('Asia/Novosibirsk'));
                        $interval = date_diff($datetime1, $datetime2);
                        $display_time = formatDisplayTime($interval);

                        echo '<div class="comment">
                        <div class="author_data">
                        <div class="author_pfp"><img src="static/user/'.$author['name'].'/'.$author['pfp'].'"></div>
                        <div class="author_name">
                        <a href="blogpage?user='.$author['id'].'">'.$author['name'].'</a>
                        <p>'.$display_time.'</p>
                        </div>
                        </div>
                        <div class="comment_data">
                        <div class="comment_text">'.$comment['text'].'</div>
                        </div>
                        <a class="accenttext respond" id="r'.$comment['id'].'">ответить</a>
                        <form id="f'.$comment['id'].'" class="respond_form hidden" action="comment?response='.$comment['id'].'&blog_id='.$blog_id.'" method="POST">
                        <input type="text" placeholder="Текст ответа" name="text">
                        <div>
                        <input type="submit" value="Ответить">
                        <button id="c'.$comment['id'].'" class="other-button respond_cancel">Отмена</button>
                        </div>
                        </form>
                        </div>';
                        $responses = $mysql->query('SELECT * FROM comment WHERE response = '.$comment['id'])->fetchAll();
                        if($responses){
                            foreach ($responses as $response) {
                                $stmt = $mysql->prepare('SELECT id, name, pfp FROM users WHERE id = :id');
                                $stmt->bindParam('id', $response['author']);
                                $stmt->execute();
                                $author = $stmt->fetch();
        
                                $time = $response['publish_date'];
                                $datetime1 = date_create($time);
                                $datetime2 = date_create('now',new DateTimeZone('Asia/Novosibirsk'));
                                $interval = date_diff($datetime1, $datetime2);
                                $display_time = formatDisplayTime($interval);

                                echo '<div class="response_comment"><div class="comment">
                                <div class="author_data">
                                <div class="author_pfp"><img src="static/user/'.$author['name'].'/'.$author['pfp'].'"></div>
                                <div class="author_name">
                                <a href="blogpage?user='.$author['id'].'">'.$author['name'].'</a>
                                <p>'.$display_time.'</p>
                                </div>
                                </div>
                                <div class="comment_data">
                                <div class="comment_text">'.$response['text'].'</div>
                                </div>
                                <a class="accenttext respond" id="r'.$response['id'].'">ответить</a>
                                <form id="f'.$response['id'].'" class="respond_form hidden" action="comment?response='.$response['id'].'&blog_id='.$blog_id.'" method="POST">
                                <input type="text" placeholder="Текст ответа" name="text">
                                <div>
                                <input type="submit" value="Ответить">
                                <button id="c'.$response['id'].'" class="other-button respond_cancel">Отмена</button>
                                </div>
                                </form>
                                </div></div>';
                            }
                        }
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
<?php } ?>