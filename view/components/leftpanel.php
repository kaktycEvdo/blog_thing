<?php
    $hidden = 0;
    if(isset($_SESSION['left_user_id']) && $_SESSION['left_user_id'] != ''){
        $luser_data = $mysql->query('SELECT name, background, pfp FROM users WHERE id = '.$_SESSION['left_user_id'])->fetch(PDO::FETCH_ASSOC);

        echo '
        <section id="left-panel">
            <div class="profile_left">
            <!-- Profile\'s media pictures -->
                <div class="profile_media">
                    <div class="background">
                        '.($luser_data['background'] != '' ? '<img src="static/'.$luser_data['background'].'" alt="profile background"/>' : '').'
                    </div>
                    <div class="pfp">
                        <img src="static/'.$luser_data['pfp'].'" alt="PFP">
                    </div>
                </div>
                <div class="brief">
                    <!-- Profile\'s name and description -->
                    <p>'.$luser_data['name'].'</p>
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
                    <a href="'.$luser_data['name'].'/works" class="generic-button other-button">Мои работы</a>
                    <a href="'.$luser_data['name'].'/message" class="generic-button">Написать мне</a>
                </div>
            </div>
        </section>';
    }