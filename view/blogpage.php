<?php
    if(isset($_POST['type'])){
        include_once 'create_post.php';
    }
    else{
        $authorDataQ = $mysql->prepare('SELECT name FROM users WHERE id = :id');
        $id = 0;
        if(isset($_GET['user'])){
            $id = $_GET['user'];
            $_SESSION['left_user_id'] = $_GET['user'];
        }
        else if (isset($_SESSION['left_user_id'])){
            $id = $_SESSION['left_user_id'];
        }
        else{
            header('Location: '.$dir);
        }
        $authorDataQ->bindParam('id', $id);
        $authorDataQ->execute();
        $author_data = $authorDataQ->fetch(PDO::FETCH_ASSOC);
        $getPostsQ = $mysql->query('SELECT * FROM posts WHERE author = '.$id.' ORDER BY id DESC', PDO::FETCH_ASSOC);
        $getStoriesQ = $mysql->query('SELECT * FROM stories WHERE author = '.$id.' ORDER BY id DESC', PDO::FETCH_ASSOC);
        
    function echoStory($id, $name, $date, $src, $author_data){
        $file = 'static/user/'.$author_data['name'].'/stories/'.$src;
        $date = date('d.m.Y', strtotime($date));
        if(preg_match('/image\//', mime_content_type($file))){
            echo '<div id="'.$id.'">
                <div>'.$name.'</div>
                <img src="'.$file.'" />
                <div>'.$date.'</div>
            </div>';
        }
        else if(preg_match('/video\//', mime_content_type($file))){
            echo '<div id="'.$id.'">
                <div>'.$name.'</div>
                <video autoplay muted src="'.$file.'"></video>
                <div>'.$date.'</div>
            </div>';
        }
        
    }
    function echoPost($post, $author_data){
        $post['last_change_date'] = date('d.m.Y', strtotime($post['last_change_date']));
        switch($post['type']){
            case 1:{
                echo '<div class="text_post '.(isset($post['media']) ? 'with_cover' : null).'"><a href="pin_post?id='.$post['id'].'">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="32" height="32" viewBox="0 0 256 256" xml:space="preserve">
                        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                            <path d="M 89.011 87.739 c -0.599 -1.371 -1.294 -2.652 -1.968 -3.891 l -0.186 -0.343 l -15.853 -15.91 c -0.371 -0.375 -0.746 -0.748 -1.12 -1.12 c -0.671 -0.667 -1.342 -1.335 -1.997 -2.018 l -1.459 -1.437 l 23.316 -23.317 l -1.704 -1.704 c -9.111 -9.112 -22.925 -12.518 -35.353 -8.759 l -6.36 -6.359 c 0.769 -7.805 -2.017 -15.69 -7.503 -21.175 L 37.123 0 L 0 37.122 l 1.706 1.704 c 5.487 5.487 13.368 8.271 21.176 7.503 l 6.36 6.36 C 25.484 65.115 28.889 78.93 38 88.041 l 1.703 1.704 l 23.316 -23.316 l 1.438 1.458 c 0.679 0.653 1.344 1.321 2.009 1.989 c 0.373 0.374 0.745 0.748 1.117 1.116 l 15.699 15.7 l 0.566 0.352 c 1.239 0.673 2.52 1.369 3.891 1.968 L 90 90 L 89.011 87.739 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        </g>
                    </svg>
                </a>
                    '.(isset($post['media']) ? "<a href='blog?id=".$post['id']."'><img src=static/user/".$author_data['name']."/covers/".$post['media']." alt='bg' class='cover'></a>" : null).'
                    <div class="inner">
                        <a href="blog?id='.$post['id'].'" class="content">'.$post['content'].'</a>
                        <div class="extras">
                            <div>
                                <div class="date">'.$post['last_change_date'].'</div>
                                '.(isset($post['tags']) ? "<div class='tags'>".$post['tags']."</div>" : null).'
                            </div>
                            '.($post['comment_ability'] ? '<a href="blog?id='.$post['id'].'&anch=comments" class="readmore">оставить комментарий</a>' : '').'
                        </div>
                    </div>
                </div>';
                break;
            }
            case 2:{
                echo '<div class="video_post"><a href="pin_post?id='.$post['id'].'">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="32" height="32" viewBox="0 0 256 256" xml:space="preserve">
                        <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                            <path d="M 89.011 87.739 c -0.599 -1.371 -1.294 -2.652 -1.968 -3.891 l -0.186 -0.343 l -15.853 -15.91 c -0.371 -0.375 -0.746 -0.748 -1.12 -1.12 c -0.671 -0.667 -1.342 -1.335 -1.997 -2.018 l -1.459 -1.437 l 23.316 -23.317 l -1.704 -1.704 c -9.111 -9.112 -22.925 -12.518 -35.353 -8.759 l -6.36 -6.359 c 0.769 -7.805 -2.017 -15.69 -7.503 -21.175 L 37.123 0 L 0 37.122 l 1.706 1.704 c 5.487 5.487 13.368 8.271 21.176 7.503 l 6.36 6.36 C 25.484 65.115 28.889 78.93 38 88.041 l 1.703 1.704 l 23.316 -23.316 l 1.438 1.458 c 0.679 0.653 1.344 1.321 2.009 1.989 c 0.373 0.374 0.745 0.748 1.117 1.116 l 15.699 15.7 l 0.566 0.352 c 1.239 0.673 2.52 1.369 3.891 1.968 L 90 90 L 89.011 87.739 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                        </g>
                    </svg>
                </a>
                    <video controls src="static/user/'.$author_data['name'].'/videos/'.$post['media'].'" loop alt="video"></video>
                    <div class="inner">
                    <div class="content">
                        '.$post['content'].'
                    </div>
                    <div class="extras">
                        <div>
                            <div class="date">'.$post['last_change_date'].'</div>
                            '.(isset($post['tags']) ? "<div class='tags'>".$post['tags']."</div>" : null).'
                        </div>
                        <div>
                        <a class="readmore" href="blog?id='.$post['id'].'">посмотреть подробнее</a>
                        '.($post['comment_ability'] ? '<a href="blog?id='.$post['id'].'&anch=comments" class="readmore">оставить комментарий</a>' : '').'
                        </div>
                    </div>
                    </div>
                </div>';
                break;
            }
        }
    }
?>
<div id="blogpage">
    <?php
        $stories = $getStoriesQ->fetchAll();
        $posts = $getPostsQ->fetchAll();

        if($stories){
            echo '<section id="stories">';
            foreach ($stories as $id => $story) {
                echoStory($id, $story['name'], $story['publish_date'], $story['media'], $author_data);
            }
            echo '</section>';
        }
        // $id == 1 => $posts[0:$limit]; $id == 2 => $posts[$limit:$limit*2]
        $id = 1;
        $limit = 4;
        if(isset($_GET['pageid'])){
            $id = $_GET['pageid'];
        }
        if(isset($_GET['limit'])){
            if($_GET['limit']=='all'){
                $limit = sizeof($posts);
            }
            else{
                $limit = $_GET['limit'];
            }
        }
    ?>
    
    <?php
    if($url == '/about'){
    ?>
    <section id="input_new">
        <input placeholder="Напишите что-нибудь" type="text">
        <button>
            <svg width="22" height="20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12.9443 8.74291C14.0183 9.81689 14.0183 11.5581 12.9443 12.6321C11.8703 13.7061 10.1291 13.7061 9.05511 12.6321C7.98114 11.5581 7.98114 9.81689 9.05511 8.74291C10.1291 7.66893 11.8703 7.66893 12.9443 8.74291Z" fill="#989898"/>
            <path d="M19.25 3.12481H17.3499L15.9742 0.37471H6.02455L4.65015 3.12616L2.75275 3.12953C1.24009 3.13224 0.00869272 4.36494 0.00738656 5.87828L0 16.8746C0 18.3913 1.23337 19.6253 2.7501 19.6253H19.25C20.7667 19.6253 22.0001 18.392 22.0001 16.8752V5.87486C22 4.35818 20.7666 3.12481 19.25 3.12481ZM10.9997 16.1877C7.9669 16.1877 5.49947 13.7203 5.49947 10.6875C5.49947 7.65475 7.9669 5.18732 10.9997 5.18732C14.0324 5.18732 16.4999 7.65475 16.4999 10.6875C16.4999 13.7203 14.0324 16.1877 10.9997 16.1877Z" fill="#989898"/>
            </svg>
        </button>
        <button>
            <svg width="17" height="17" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.6479 7.94818L0.862529 0.662644C0.611178 0.548504 0.311256 0.616502 0.136404 0.83264C-0.0396636 1.04878 -0.0457348 1.35598 0.121832 1.57819L5.31278 8.49945L0.121832 15.4207C-0.0457348 15.6429 -0.0396636 15.9513 0.135189 16.1663C0.252972 16.3132 0.429039 16.3921 0.607535 16.3921C0.693747 16.3921 0.779959 16.3739 0.861314 16.3363L16.6466 9.05072C16.8628 8.95115 17 8.73623 17 8.49945C17 8.26267 16.8628 8.04775 16.6479 7.94818Z" fill="white"/>
            </svg>
        </button>
    </section>
    <?php
    }
    ?>
    <div id="new_post" class="modal">
        <div class="modal_content">
            <div class="interface">
                <div></div>
                <button class="modal_close">x</button>
            </div>
            <?php include_once 'view/modal/new_post.php'; ?>
        </div>
    </div>
    <div id="post_content" class="modal">
        <div class="modal_content">
            <div class="interface">
                <div></div>
                <button class="modal_close">x</button>
            </div>
            <?php include_once 'view/modal/post_content.php'; ?>
        </div>
    </div>
    <div id="edit_post" class="modal">
        <div class="modal_content">
            <div class="interface">
                <div></div>
                <button class="modal_close">x</button>
            </div>
            <?php include_once 'view/modal/edit_post.php'; ?>
        </div>
    </div>
    <?php
        foreach ($stories as $id => $content) {
            echo '<div id="story'.$id.'" class="modal modal_stories">
                    <div class="modal_content">';
            include 'view/modal/story.php';
            echo '</div>
                </div>';
        }
    ?>
    <section id="do_limit">
        <div>
            <h2>Задать лимит постов:</h2>
        </div>
        <div>
        <!-- переделать позже -->
            <a href="?limit=1&pageid=1">1</a>
            <a href="?limit=2&pageid=1">2</a>
            <a href="?limit=4&pageid=1">4</a>
            <a href="?limit=8&pageid=1">8</a>
            <a href="?limit=all">Все</a>
        </div>
    </section>
    <section id="blogs">
        <div class="posts">
            <?php
                for($i = ($id-1)*$limit; $i < $id*$limit; $i++){
                    if(!isset($posts[$i])) break;
                    echoPost($posts[$i], $author_data);
                }
            ?>
        </div>
            <?php
                if(sizeof($posts) > $limit){
                    echo '<div class="pagination">';
                    if($id != 1){
                        echo '<a href="?pageid=1&limit='.$limit.'">&lt;</a>';
                        echo '<a href="?pageid='.($id-1).'&limit='.$limit.'">'.($id-1).'</a>';
                    }
                    echo '<a href="#" class="active">'.$id.'</a>';
                    $temp = (sizeof($posts)/$limit) - (sizeof($posts)%$limit);
                    if($id < $temp){
                        echo '<a href="?pageid='.($id+1).'&limit='.$limit.'">'.($id+1).'</a>';
                        echo '<a href="?pageid='.$temp.'&limit='.$limit.'">&gt;</a>';
                    }
                    echo '</div>';
                }
            ?>
    </section>
</div>
<script>
    let section = document.querySelector("#input_new");
    let stories = document.querySelectorAll('#stories > div');

    let closeButton = document.querySelectorAll(".modal_close");

    let modal = document.querySelector("#new_post");
    let modal_stories = document.querySelectorAll(".modal_stories");
    var modals = document.querySelectorAll(".modal");

    section.addEventListener('click', () => openModal(modal));
    stories.forEach(story => {
        story.addEventListener('click', (e) => {
            openModal(modal_stories[e.currentTarget.id]);
        }); 
    });

    closeButton.forEach(btn => {
        btn.addEventListener('click', () => closeModal()); 
    });
    window.addEventListener('keydown', (e) => {
        if(e.key == 'Escape'){
            closeModal();
        }
    });
</script>
<?php } ?>