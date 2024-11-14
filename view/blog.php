<?php
if(!isset($_GET['id'])){
    header('Location: ../'.$dir);
}
else{
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
                <a href="javascript:history.back()">вернуться обратно</a>
                <a href="pin_post?id=<?php echo $blog['id']?>">
                    <?php echo $blog['pinned'] != 1 ? 'закрепить' : 'закреплено'?>
                </a>
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
            <?php
            if($blog['comment_ability'] == 1){
                $getCommentsQ = $mysql->prepare('SELECT * FROM comment WHERE post = :id');
                $getCommentsQ->bindParam('id', $post_id);
                $getCommentsQ->execute();

                $comments = $getCommentsQ->fetchAll();

                echo '<div class="comment_section"><h3>Обсуждение</h3><form><input type="text" placeholder="Текст комментария"><input type="submit"></form>';
                if(!$comments || sizeof($comments) == 0){
                    echo '<div class="no_comments">Нет комментариев под постом :(</div>';
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