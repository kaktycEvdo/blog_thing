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
            header('Location: ../');
        }
        $authorDataQ->bindParam('id', $id);
        $authorDataQ->execute();
        $author_data = $authorDataQ->fetch(PDO::FETCH_ASSOC);
        $getPostsQ = $mysql->query('SELECT id,  FROM blogs, videos WHERE author = '.$_SESSION['user_id'].' ORDER BY last_change_date', PDO::FETCH_ASSOC);
        
    function echoStory($name, $date, $src){
        echo '<div>
            <div>'.$name.'</div>
            <video src="'.$src.'"></video>
            <div>'.$date.'</div>
        </div>';
    }
    function echoPost($post, $author_data){
        switch($post['type']){
            case 1:{
                echo '<div class="text_post '.(isset($post['cover']) ? 'with_cover' : null).'">
                    '.(isset($post['cover']) ? "<img src=static/user/".$author_data['name']."/covers/".$post['cover']." alt='bg' class='cover'>" : null).'
                    <div class="content">'.$post['content'].'</div>
                    <div class="extras">
                        <div>
                            <div class="date">'.$post['last_change_date'].'</div>
                            '.(isset($post['tags']) ? "<div class='tags'>".$post['tags']."</div>" : null).'
                        </div>
                    </div>
                </div>';
                break;
            }
            case 2:{
                echo '<div class="video_post">
                    <video src="'.$post['video'].'" loop alt="video"></video>
                    <div class="content">
                        '.$post['content'].'
                    </div>
                    <div class="extras">
                        <div>
                            <div class="date">'.$post['date'].'</div>
                            '.(isset($post['tags']) ? "<div class='tags'>".$post['tags']."</div>" : null).'
                        </div>
                        <a href="#" class="readmore">оставить комментарий</a>
                    </div>
                </div>';
                break;
            }
        }
    }
?>
<div id="blogpage">
    <section id="stories">
        <?php
            $stories = [
                ["name" => "пупупу", "src" => "static/user/admin/1099581_Architecture_Building_1080x1920.mp4", "date" => "20.06.2020"],
                ["name" => "хехехе", "src" => "static/user/admin/6916114_Motion Graphics_Motion Graphic_1080x1920.mp4", "date" => "20.06.2020"],
                ["name" => "пупупу", "src" => "static/user/admin/1099581_Architecture_Building_1080x1920.mp4", "date" => "20.06.2020"],
                ["name" => "пупупу", "src" => "static/user/admin/1099581_Architecture_Building_1080x1920.mp4", "date" => "20.06.2020"]
            ];
            
            $posts = $getPostsQ->fetchAll();

            // $posts = [
            //     ["type" => 1, "content" => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati quisquam ratione ex aperiam eum cupiditate in tempore delectus placeat eaque, dolorum voluptatum at officia impedit, nobis sequi debitis, dolores nostrum reiciendis quo rerum ab laborum veritatis. Quam tempore tenetur amet, culpa quo quisquam voluptatum necessitatibus dolore ipsum possimus beatae eaque.', "date" => "21.06.2020"],
            //     ["type" => 1, "content" => '<h3>Как писать код быстро и безболезненно?</h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati quisquam ratione ex aperiam eum cupiditate in tempore delectus placeat eaque, dolorum voluptatum at officia impedit, nobis sequi debitis, dolores nostrum reiciendis quo rerum ab laborum veritatis. Quam tempore tenetur amet, culpa quo quisquam voluptatum necessitatibus dolore ipsum possimus beatae eaque.', "date" => "21.06.2020", "cover" => 'static/user/admin/A_laptop.jpg', "tags" => 'создание сайтов'],
            //     ["type" => 2, "content" => '<h2>Купил новый ноутбук?</h2>', "date" => '21.06.2020', "video" => 'static/user/admin/6916114_Motion Graphics_Motion Graphic_1080x1920.mp4', "tags" => 'продвижение видео'],
            //     ["type" => 1, "content" => '<h3>Сходил на конференцию?</h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati quisquam ratione ex aperiam eum cupiditate in tempore delectus placeat eaque, dolorum voluptatum at officia impedit, nobis sequi debitis, dolores nostrum reiciendis quo rerum ab laborum veritatis. Quam tempore tenetur amet, culpa quo quisquam voluptatum necessitatibus dolore ipsum possimus beatae eaque.', "date" => "21.06.2020", "cover" => 'static/user/admin/A_laptop.jpg', "tags" => 'создание сайтов'],
            //     ["type" => 1, "content" => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati quisquam ratione ex aperiam eum cupiditate in tempore delectus placeat eaque, dolorum voluptatum at officia impedit, nobis sequi debitis, dolores nostrum reiciendis quo rerum ab laborum veritatis. Quam tempore tenetur amet, culpa quo quisquam voluptatum necessitatibus dolore ipsum possimus beatae eaque.', "date" => "21.06.2020"],
            //     ["type" => 1, "content" => '<h3>Статья на второй странице?</h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati quisquam ratione ex aperiam eum cupiditate in tempore delectus placeat eaque, dolorum voluptatum at officia impedit, nobis sequi debitis, dolores nostrum reiciendis quo rerum ab laborum veritatis. Quam tempore tenetur amet, culpa quo quisquam voluptatum necessitatibus dolore ipsum possimus beatae eaque.', "date" => "21.06.2020", "cover" => 'static/user/admin/A_laptop.jpg', "tags" => 'создание сайтов'],
            //     ["type" => 2, "content" => '<h2>Купил новый ноутбук?</h2>', "date" => '21.06.2020', "video" => 'static/user/admin/6916114_Motion Graphics_Motion Graphic_1080x1920.mp4', "tags" => 'продвижение видео'],
            //     ["type" => 1, "content" => '<h3>Сходил на конференцию?</h3>Lorem ipsum dolor sit amet consectetur adipisicing elit. Obcaecati quisquam ratione ex aperiam eum cupiditate in tempore delectus placeat eaque, dolorum voluptatum at officia impedit, nobis sequi debitis, dolores nostrum reiciendis quo rerum ab laborum veritatis. Quam tempore tenetur amet, culpa quo quisquam voluptatum necessitatibus dolore ipsum possimus beatae eaque.', "date" => "21.06.2020", "cover" => 'static/user/admin/A_laptop.jpg', "tags" => 'создание сайтов']
            // ];
            foreach ($stories as $_ => $story) {
                echoStory($story['name'], $story['date'], $story['src']);
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
    </section>
    
    <?php
    if($uri == '/about'){
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
                <button class="new_post_close">x</button>
            </div>
            <?php include_once 'view/modal/new_post.php'; ?>
        </div>
    </div>
    <div id="post_content" class="modal">
        <div class="modal_content">
            <div class="interface">
                <div></div>
                <button class="new_post_close">x</button>
            </div>
            <?php include_once 'view/modal/post_content.php'; ?>
        </div>
    </div>
    <div id="edit_post" class="modal">
        <div class="modal_content">
            <div class="interface">
                <div></div>
                <button class="new_post_close">x</button>
            </div>
            <?php include_once 'view/modal/edit_post.php'; ?>
        </div>
    </div>
    <div id="story" class="modal">
        <div class="modal_content">
            <div class="interface">
                <div></div>
                <button class="new_post_close">x</button>
            </div>
            <?php include_once 'view/modal/story.php'; ?>
        </div>
    </div>
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
    let closeButton = document.querySelector(".new_post_close");
    var modal = document.querySelector("#new_post");
    function openModal(){
        modal.classList.add('shown');
        document.body.scroll;
    }
    function closeModal(){
        modal.classList.remove('shown');
    }
    section.addEventListener('click', () => openModal());
    closeButton.addEventListener('click', () => closeModal());
    window.addEventListener('keydown', (e) => {
        if(e.key == 'Escape'){
            closeModal();
        }
    });
</script>
<?php } ?>