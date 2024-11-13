<?php
    $hidden = 0;
    if(isset($_SESSION['left_user_id']) && $_SESSION['left_user_id'] != ''){
        $luser_data = $mysql->query('SELECT name, background, pfp, description, brief FROM users WHERE id = '.$_SESSION['left_user_id'])->fetch(PDO::FETCH_ASSOC);
?>
        <section id="left-panel">
            <div class="profile_left">
                <div class="profile_media">
                    <div class="background">
                        <?php echo $luser_data['background'] != '' ? '<img src="static/user/'.$luser_data['name'].'/'.$luser_data['background'].'" alt="profile background"/>' : ''; ?>
                    </div>
                    <div class="pfp">
                        <?php echo $luser_data['pfp'] != '' && $luser_data['pfp'] != 'user-default.png' ? '<img src="static/user/'.$luser_data['name'].'/'.$luser_data['pfp'].'" alt="PFP">' : '<img src="static/user-default.png" alt="PFP">'; ?>
                    </div>
                </div>
                <div class="brief">
                    <p><?php echo $luser_data['name'] ?></p>
                    <p><?php echo $luser_data['brief'] ?></p>
                    <div class="contacts">
                        <a href="custom link"><img src="" alt="Twitter link"></a>
                        <a href="custom link"><img src="" alt="VK link"></a>
                        <a href="custom link"><img src="" alt="Pinterest link"></a>
                    </div>
                </div>
                <hr>
                <div class="big_desc">
                    <?php echo $luser_data['description'] ?>
                </div>
                <hr>
                <div class="profile_left_buttons">
                    <a href="<?php echo $luser_data['name']; ?>/works" class="generic-button other-button">Мои работы</a>
                    <a href="#" class="generic-button" id="contact_button">Написать мне</a>
                </div>
            </div>
            <div id="contact" class="modal">
                <div class="modal_content">
                    <div class="interface">
                        <div></div>
                        <button class="modal_close">x</button>
                    </div>
                    <?php include 'view/modal/contact.php'; ?>
                </div>
            </div>
        </section>
        <script>
            let contact_modal = document.querySelector('.modal#contact');
            let contact_op_button = document.querySelector('#contact_button');
            contact_op_button.addEventListener('click', () => openModal(contact_modal));
        </script>
    <?php }