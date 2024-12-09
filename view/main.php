<section class="main_users">
<?php
    $query = $pdo->query('SELECT id, background, pfp, name, brief FROM users');
    $users = $query->fetchAll();
    foreach ($users as $_ => $user) {
        ?>        
            <a href="blogpage?user=<?php echo $user['id']; ?>">
                <div class="profile_media">
                    <div class="pfp">
                        <img src="static/<?php echo isset($user['pfp']) && $user['pfp'] != 'user-default.png' ? 'user/'.$user['name'].'/'.$user['pfp'] : 'user-default.png' ?>" />
                    </div>
                </div>
                <div class="brief">
                    <p><?php echo $user['name'] ?></p>
                    <textarea disabled><?php echo $user['brief'] ?></textarea>
                </div>
            </a>
        <?php
    }
    if(sizeof($users) == 0){
        ?>
            <div>
                Нет пользователей :(
            </div>
        <?php
    }
?>
</section>