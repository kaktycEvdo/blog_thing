<?php
    $hidden = 0;
    if(!isset($_SESSION['left_user_id']) || $_SESSION['left_user_id'] == -1) $hidden = 1;
    else $luser_data = $mysql->query('SELECT name, bg, pfp FROM users WHERE id = '.$_SESSION['left_user_id'])->fetch(PDO::FETCH_ASSOC);
?>
<section id="left-panel" class="<?php if($hidden) echo 'hidden'; ?>">
    <div class="profile_left">
    <!-- Profile's media pictures -->
        <div class="profile_media">
            <div class="background">
                <img src="static/<?php echo $luser_data['bg'] ?>" alt="Profile's bg">
            </div>
            <div class="pfp">
                <img src="static/<?php echo $luser_data['pfp'] ?>" alt="PFP">
            </div>
        </div>
        <div class="brief">
            <!-- Profile's name and description -->
            <p><?php echo $luser_data['name'] ?></p>
            <p>Краткое описание</p>
            <div class="contacts">
                <a href="custom link"><img src="" alt="Twitter link"></a>
                <a href="custom link"><img src="" alt="VK link"></a>
                <a href="custom link"><img src="" alt="Pinterest link"></a>
            </div>
        </div>
        <hr>
        <div class="big_desc">
            Lorem ipsum dolor sit amet consectetur adipisicing elit. Eaque nesciunt quis perspiciatis voluptate temporibus fuga officia vero non consectetur. Voluptatibus?
        </div>
        <hr>
        <div class="profile_left_buttons">
            <a href="<?php echo $luser_data['name'] ?>/works" class="generic-button other-button">Мои работы</a>
            <a href="<?php echo $luser_data['name'] ?>/message" class="generic-button">Написать мне</a>
        </div>
    </div>
</section>