<section class="main_users">
<?php
    $query = $mysql->query('SELECT id, background, pfp, name, brief FROM users');
    $users = $query->fetchAll();
    foreach ($users as $_ => $user) {
        ?>        
            <a href="blogpage?user=<?php echo $user['id']; ?>">
                <div class="profile_media">
                    <div class="background">
                        <img src="static/<?php echo $user['background']; ?>">
                    </div>
                    <div class="pfp">
                        <img src="static/<?php echo isset($user['pfp']) ? $user['pfp'] : 'user-default.png' ?>">
                    </div>
                </div>
                <div class="brief">
                    <!-- Profile's name and description -->
                    <p><?php echo $user['name'] ?></p>
                    <p><?php echo $user['brief'] ?></p>
                    <div class="contacts">
                        <a href="custom link"><img src="" alt="Twitter link"></a>
                        <a href="custom link"><img src="" alt="VK link"></a>
                        <a href="custom link"><img src="" alt="Pinterest link"></a>
                    </div>
                </div>
            </a>
        <?php
    }
?>
</section>