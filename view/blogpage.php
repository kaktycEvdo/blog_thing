<?php
    // $id == 1 => $posts[0:$limit]; $id == 2 => $posts[$limit:$limit*2]
    $page_id = 1;
    $limit = 4;
    if(isset($_GET['pageid'])){
        $page_id = $_GET['pageid'];
    }
    if(isset($_GET['limit'])){
        if($_GET['limit']=='all'){
            $limit = sizeof($posts);
        }
        else{
            $limit = $_GET['limit'];
        }
    }

    $temp = ($page_id-1)*$limit;

    class Story{
        private $id;
        private $name;
        private $media;
        private $comment_ability;
        private $publish_date;
        private $author;

        public function __construct(array $story_info = null, User $luser = null) {
            if($story_info != null){
                // var_dump($story_info);
                $this->id = $story_info['id'];
                $this->name = $story_info['name'];
                $this->media = $story_info['media'];
                $this->comment_ability = $story_info['comment_ability'];
                $this->publish_date = $story_info['publish_date'];
            }

            if($luser != null){
                $this->author = $luser;
            }
            else{
                $this->author = unserialize($_SESSION['left_user']);
            }
        }

        public function printout(){
            $file = "static/user/".$this->author->name."/stories/$this->media";
            $date = date('d.m.Y', strtotime($this->publish_date));
            echo "<div id=$this->id>
                <div>$this->name</div>
                ".(preg_match('/video\//', mime_content_type($file)) ? "<video autoplay muted src=$file></video>" : "<img src=$file />")."
                <div>$date</div>
            </div>";
        }
        public function modalPrintout(){
            echo "<div id='story$this->id' class='modal modal_stories'>
                    <div class='modal_content'>";
            include 'view/modal/story.php';
            echo "</div>
                </div>";
        }
    }

    class Post{
        private $id;
        private $author;
        private $content;
        private $header;
        private $media;
        private $tags;
        private $pinned;
        private $comment_ability;
        private $last_change_date;

        public function __construct(array $post_info = null, User $luser = null, ...$args) {
            if($post_info != null){
                $this->id = $post_info['id'];
                $this->content = $post_info['content'];
                $this->media = $post_info['media'];
                $this->header = $post_info['header'];
                $this->pinned = $post_info['pinned'];
                $this->comment_ability = $post_info['comment_ability'];
                $this->last_change_date = $post_info['last_change_date'];
            }
            $this->author = $luser != null ? $luser : unserialize($_SESSION['left_user']);
        }
        
        function printout(){
            $date = date('d.m.Y', strtotime($this->last_change_date));
            $media_type = null;
            $isabout = str_contains(explode('/', $_SERVER['REQUEST_URI'], 2)[1], 'about');
            $file = null;
            if($this->media != null){
                $file = "static/user/".$this->author->name."/post_media/".$this->media;
                $media_type = explode('/', mime_content_type($file))[0];
            }
            ?>
                <div class="<?php
                    if($media_type != null){
                        switch ($media_type){
                            case 'image':
                                echo "with_cover";
                                break;
                            case 'video':
                                echo "video_post";
                                break;
                        }
                    }
                ?>"><?php if($isabout){?><a href="pin_post?id=<?php echo $this->id ?>" <?php echo $this->pinned == 1 ? ' class="pin pinned"' : ' class "pin"' ?>>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="32" height="32" viewBox="0 0 256 256" xml:space="preserve">
                            <g style="stroke: none; stroke-width: 0; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill: none; fill-rule: nonzero; opacity: 1;" transform="translate(1.4065934065934016 1.4065934065934016) scale(2.81 2.81)" >
                                <path d="M 89.011 87.739 c -0.599 -1.371 -1.294 -2.652 -1.968 -3.891 l -0.186 -0.343 l -15.853 -15.91 c -0.371 -0.375 -0.746 -0.748 -1.12 -1.12 c -0.671 -0.667 -1.342 -1.335 -1.997 -2.018 l -1.459 -1.437 l 23.316 -23.317 l -1.704 -1.704 c -9.111 -9.112 -22.925 -12.518 -35.353 -8.759 l -6.36 -6.359 c 0.769 -7.805 -2.017 -15.69 -7.503 -21.175 L 37.123 0 L 0 37.122 l 1.706 1.704 c 5.487 5.487 13.368 8.271 21.176 7.503 l 6.36 6.36 C 25.484 65.115 28.889 78.93 38 88.041 l 1.703 1.704 l 23.316 -23.316 l 1.438 1.458 c 0.679 0.653 1.344 1.321 2.009 1.989 c 0.373 0.374 0.745 0.748 1.117 1.116 l 15.699 15.7 l 0.566 0.352 c 1.239 0.673 2.52 1.369 3.891 1.968 L 90 90 L 89.011 87.739 z" style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-linejoin: miter; stroke-miterlimit: 10; fill-rule: nonzero; opacity: 1;" transform=" matrix(1 0 0 1 0 0) " stroke-linecap="round" />
                            </g>
                        </svg>
                    </a>
                    <?php
                }
                        if($media_type != null){
                            switch ($media_type){
                                case 'image':
                                    echo "<a href='blog?id=$this->id'><img src='$file' alt='bg' class='cover'></a>";
                                    break;
                                case 'video':
                                    echo "<video controls src='$file' loop alt='video'></video>";
                                    break;
                            }
                        }
                    ?>
                        <div class="inner">
                            <a href="blog?id=<?php echo $this->id ?>" class="content">
                            <h3><?php echo $this->header; ?></h3>
                            <?php echo $this->content; ?>
                            </a>
                            <div class="extras">
                                <div>
                                    <div class="date"><?php echo $this->last_change_date; ?></div>
                                    <?php echo isset($this->tags) ? "• <div class='tags'>$this->tags</div>" : null; ?>
                                </div>
                                <?php echo $this->comment_ability ? "<a href='blog?id=$this->id#comments' class='readmore'>оставить комментарий</a>" : null ?>
                            </div>
                        </div>
                    </div>
            <?php }
    }

    $getPostsQ = $pdo->query("SELECT id, content, header, media, tags, comment_ability, last_change_date, pinned FROM posts WHERE author = $luser->id ORDER BY id DESC LIMIT $limit OFFSET $temp", PDO::FETCH_ASSOC);
    $getPostsAmount = $pdo->query("SELECT count(id) as amount FROM posts WHERE author = $luser->id GROUP BY author");
    $postAmount = $getPostsAmount->fetch();
    if($postAmount != null){
        $postAmount = $postAmount[0];
    }

    $getStoriesQ = $pdo->query("SELECT id, name, media, comment_ability, publish_date FROM stories WHERE author = $luser->id ORDER BY id DESC", PDO::FETCH_ASSOC);
    $getStoriesAmount = $pdo->query("SELECT count(id) as amount FROM stories WHERE author = $luser->id GROUP BY author");
    $storyAmount = $getStoriesAmount->fetch();
    if($storyAmount != null){
        $storyAmount = $storyAmount[0];
    }

    $stories_data = $getStoriesQ->fetchAll();
    $posts_data = $getPostsQ->fetchAll();

    $stories = [];
    $posts = [];

    if($stories_data){
        foreach ($stories_data as $story) {
            array_push($stories, new Story($story));
        }
    }
    if($posts_data){
        foreach ($posts_data as $post) {
            array_push($posts, new Post($post));
        }
    }
?>
<div id="blogpage">
    <?php
        if(sizeof($stories) > 0){
            echo '<section id="stories">';
            foreach ($stories as $story) {
                $story->printout();
            }
            echo '</section>';
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
    if(sizeof($stories) == 0 && sizeof($posts) == 0){
        echo "<h2>Нет постов у пользователя</h2>";
    }
    else{
    ?>
    <div id="new_post" class="modal">
        <div class="modal_content">
            <div class="interface">
                <div></div>
                <button class="modal_close">×</button>
            </div>
            <?php include_once 'view/modal/new_post.php'; ?>
        </div>
    </div>
    <div id="post_content" class="modal">
        <div class="modal_content">
            <div class="interface">
                <div></div>
                <button class="modal_close">×</button>
            </div>
            <?php include_once 'view/modal/post_content.php'; ?>
        </div>
    </div>
    <div id="edit_post" class="modal">
        <div class="modal_content">
            <div class="interface">
                <div></div>
                <button class="modal_close">×</button>
            </div>
            <?php include_once 'view/modal/edit_post.php'; ?>
        </div>
    </div>
    <?php
        foreach ($stories as $story) {
            $story->modalPrintout();
        }
    ?>
    <section id="do_limit">
        <div>
            <h2>Задать лимит постов:</h2>
        </div>
        <div>
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
                foreach ($posts as $post) {
                    $post->printout();
                }
            ?>
        </div>
            <?php
                if($postAmount > $limit){
                    echo '<div class="pagination">';
                    if($page_id != 1){
                        echo "<a href=?pageid=1&limit=$limit>&lt;</a>";
                        echo "<a href=?pageid=".($page_id-1)."&limit=$limit>".($page_id-1)."</a>";
                    }
                    echo "<a class='active'>$page_id</a>";
                    $temp = ($postAmount/$limit - (int)($postAmount/$limit))*$limit;
                    if($page_id <= $temp){
                        echo '<a href="?pageid='.($page_id+1)."&limit=$limit\">".($page_id+1).'</a>';
                        echo "<a href=?pageid=$temp&limit=$limit>&gt;</a>";
                    }
                    echo '</div>';
                }
            ?>
    </section>
</div>
<script>
    let section = document.querySelector("#input_new");
    let stories = document.querySelectorAll("#stories > div");

    let modal = document.querySelector("#new_post");
    let modal_stories = document.querySelectorAll(".modal_stories");

    if(section){
        section.addEventListener('click', () => openModal(modal));
    }

    stories.forEach(story => {
        story.addEventListener('click', (e) => {
            openModal(modal_stories[e.currentTarget.id]);
        }); 
    });
</script><?php }?>