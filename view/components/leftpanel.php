<?php
    $hidden = 0;
    if(isset($_SESSION['left_user']) && $_SESSION['left_user'] != ''){
        $luser = unserialize($_SESSION['left_user']);
?>
        <section id="left-panel">
            <div class="profile_left">
                <div class="profile_media">
                    <div class="background">
                        <?php echo $luser->background != '' ? "<img src='static/user/$luser->name/$luser->background' alt='profile background'/>" : ''; ?>
                    </div>
                    <div class="pfp">
                        <?php echo $luser->pfp != '' && $luser->pfp != 'user-default.png' ? "<img src='static/user/$luser->name/$luser->pfp' alt='profile picture'/>" : '<img src="static/user-default.png" alt="PFP">'; ?>
                    </div>
                </div>
                <div class="brief">
                    <p><?php echo $luser->name ?></p>
                    <p><?php echo $luser->brief ?></p>
                    <div class="contacts">
                        <a href="custom link"><img src="" alt="Twitter link"></a>
                        <a href="custom link"><img src="" alt="VK link"></a>
                        <a href="custom link"><img src="" alt="Pinterest link"></a>
                    </div>
                </div>
                <hr>
                <div class="big_desc">
                    <?php echo $luser->description ?>
                </div>
                <hr>
                <div class="profile_left_buttons">
                    <a href="works?user=<?php echo $luser->id; ?>" class="generic-button other-button">Мои работы</a>
                    <a href="#" class="generic-button" id="contact_button">Написать мне</a>
                </div>
            </div>
            <div id="contact" class="modal">
                <div class="modal_content">
                    <div class="interface">
                        <div></div>
                        <button class="modal_close">×</button>
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