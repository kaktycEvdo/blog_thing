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
?>
        <div class="post">
            <a href="pin_post?id=<?php echo $blog['id'] ?>"<?php echo $blog['pinned'] == 1 ? ' class="pinned"' : null?>>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="32" height="32" viewBox="0 0 256 256" xml:space="preserve">
                    <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                        <path d="M 89.011 87.739 c -0.599 -1.371 -1.294 -2.652 -1.968 -3.891 l -0.186 -0.343 l -15.853 -15.91 c -0.371 -0.375 -0.746 -0.748 -1.12 -1.12 c -0.671 -0.667 -1.342 -1.335 -1.997 -2.018 l -1.459 -1.437 l 23.316 -23.317 l -1.704 -1.704 c -9.111 -9.112 -22.925 -12.518 -35.353 -8.759 l -6.36 -6.359 c 0.769 -7.805 -2.017 -15.69 -7.503 -21.175 L 37.123 0 L 0 37.122 l 1.706 1.704 c 5.487 5.487 13.368 8.271 21.176 7.503 l 6.36 6.36 C 25.484 65.115 28.889 78.93 38 88.041 l 1.703 1.704 l 23.316 -23.316 l 1.438 1.458 c 0.679 0.653 1.344 1.321 2.009 1.989 c 0.373 0.374 0.745 0.748 1.117 1.116 l 15.699 15.7 l 0.566 0.352 c 1.239 0.673 2.52 1.369 3.891 1.968 L 90 90 L 89.011 87.739 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                    </g>
                </svg>
            </a>
            <?php
            if(isset($blog['media'])){
                echo '<img src="static/user/'.$author_data['name'].'/covers/'.$blog['media'].'">';
            }
            ?>
            <div class="inner">
                <div class="content">
                    <?php echo $blog['content'] ?>
                </div>
                    <div class="extras">
                        <div>
                            <div class="date"><?php echo $blog['last_change_date'] ?></div>
                            <?php echo isset($blog['tags']) ? "<div class='tags'>".$blog['tags']."</div>" : null ?>
                        </div>
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