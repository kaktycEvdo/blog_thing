<?php
if(!isset($_GET['id'])){
    header('Location: ../'.$dir);
}
else{
    function formatDisplayTime($interval){
        $res = '';
        if($interval['y'] > 0){
            switch ($interval['y']){
                case 1:
                    $res = "1 год назад"; break;
                case 2:
                case 3:
                    $res = $interval['y']." года назад"; break;
                default:
                    $res = $interval['y']." лет назад"; break;
            }
        }
        else if($interval['m'] > 0){
            switch ($interval['m']){
                case 1:
                    $res = "1 месяц назад"; break;
                case 2:
                case 3:
                case 4:
                    $res = $interval['m']." месяца назад"; break;
                default:
                    $res = $interval['m']." месяцев назад"; break;
            }
        }
        else if($interval['d'] > 0){
            switch ($interval['d']){
                case 1:
                    $res = "1 день назад"; break;
                case 2:
                case 3:
                case 4:
                    $res = $interval['d']." дня назад"; break;
                default:
                    $res = $interval['d']." дней назад"; break;
            }
        }
        else if($interval['h'] > 0){
            switch ($interval['h']){
                case 1:
                    $res = "час назад"; break;
                case 2:
                case 3:
                case 4:
                    $res = $interval['h']." часа назад"; break;
                default:
                    $res = $interval['h']." часов назад"; break;
            }
        }
        return $res;
    }

    $blog_id = $_GET['id'];
    $blogQ = $mysql->prepare('SELECT * FROM posts WHERE id = :id');
    $blogQ->bindParam('id', $blog_id);

    $blogQ->execute();
    $blog = $blogQ->fetch();

    $authorDataQ = $mysql->prepare('SELECT name, pfp FROM users WHERE id = :id');
    $authorDataQ->bindParam('id', $blog['author']);
    $authorDataQ->execute();
    $author_data = $authorDataQ->fetch();

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
        <div class="post">
            <div class="post_interface">
                <div>
                    <a href="javascript:history.back()">вернуться обратно</a>
                    <a href="pin_post?id=<?php echo $blog['id']?>">
                        <?php echo $blog['pinned'] != 1 ? 'закрепить' : 'закреплено'?>
                    </a>
                </div>
                <div>
                    <a href="#">поделиться</a>
                </div>
            </div>
            <?php echo isset($blog['header']) ? "<h3>".$blog['header']."</h3>" : null ?>
            <div class="extras">
                <div class="date"><?php echo $blog['last_change_date'] ?></div>
                <?php echo isset($blog['tags']) ? "<div class='tags'>".$blog['tags']."</div>" : null ?>
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
                $getCommentsQ = $mysql->prepare('SELECT * FROM comment WHERE post = :id');
                $getCommentsQ->bindParam('id', $blog_id);
                $getCommentsQ->execute();

                $comments = $getCommentsQ->fetchAll();

                echo '<div class="comment_section"><h3>Обсуждение</h3><form method="POST" action="comment?blog_id='.$blog['id'].'"><input type="text" placeholder="Текст комментария" name="text" id="text"><input type="submit" value="Отправить"></form>';
                if(!$comments || sizeof($comments) == 0){
                    echo '<div class="no_comments">Нет комментариев под постом :(</div>';
                }
                else{
                    foreach($comments as $comment){
                        $stmt = $mysql->prepare('SELECT name, pfp FROM users WHERE id = :id');
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
                        <p>'.$author['name'].'</p>
                        <p>'.$interval->format('%y лет %M месяцев %D дней %h часов %i минут назад').'</p>
                        </div>
                        </div>
                        <div class="comment_data">
                        <div class="comment_text">'.$comment['text'].'</div>
                        </div>
                        <a class="readmore" href="respond?id='.$comment['id'].'">ответить</a>
                        </div>';
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
        </div>
<?php } ?>