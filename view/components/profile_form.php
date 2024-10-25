<div class="profile_page">
    <h1>Профиль</h1>
    <form method="POST" enctype="multipart/form-data">
        <div>
            <input type="text" name="name" value="<?php echo $user_data['name'] ?>" placeholder="Имя профиля">
            <input type="email" name="email"  value="<?php echo $user_data['email'] ?>" placeholder="Электронная почта">
            <input type="password" name="newPassword" value="" placeholder="Новый пароль" autocomplete="new-password">
            <input type="password" name="repeatPassword" value="" placeholder="Подтвердите пароль" autocomplete="new-password">
            <div>
                <input type="submit" value="Сохранить">
                <button>Сбросить</button>
            </div>
        </div>
        <div class="image-select">
            <div>
                <select class="generic-button" name="select" id="select">
                    <option value="1">Аватарка</option>
                    <option value="2">Фон</option>
                </select>
            </div>
            <div class="pfp">
                <img id="pfp-output" src="static/<?php echo $user_data['pfp'] ?>" alt="Profile avatar">
                <div>
                    <input value="<?php echo $user_data['pfp'] ? $user_data['pfp'] : 'default' ?>" accept="image/*" type="file" limit="20000" name="profile_image" id="profile_image">
                    <label for="profile_image">
                        Выбрать аватар
                    </label>
                    <button>
                        Сбросить
                    </button>
                </div>
            </div>
            <div class="bg">
                <img id="bg-output" src="static/<?php echo $user_data['bg'] ?>" alt="Profile background">
                <div>
                    <input value="<?php echo $user_data['bg'] ? $user_data['bg'] : 'default' ?>" accept="image/*" type="file" limit="20000" name="profile_bg" id="profile_bg">
                    <label for="profile_bg">
                        Выбрать фон профиля
                    </label>
                    <button>
                        Сбросить
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
<script>
    var i = 1;
    let pfp = document.getElementsByClassName('pfp')[0];
    let bg = document.getElementsByClassName('bg')[0];
    function changeImages(){
        if(i == 1){
            pfp.classList.remove('hidden');
            bg.classList.add('hidden');
        }
        else{
            pfp.classList.add('hidden');
            bg.classList.remove('hidden');
        }
    }
    document.querySelector('select').addEventListener('change', (e) => {
        i = e.target.value;
        changeImages();
    });
    changeImages();
    document.querySelector('#profile_image').addEventListener('change', (e) => {
        let output = document.getElementById("pfp-output");
        output.src = URL.createObjectURL(e.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    });
    document.querySelector('.pfp button').addEventListener('click', (e) => {
        e.preventDefault();
        document.querySelector('.image-select input').files[0] = null;
        let output = document.getElementById("pfp-output");
        output.src = 'static/user-default.png';
        output.onload = function() {
            URL.revokeObjectURL(output.src);
        }
    });
    document.querySelector('#profile_bg').addEventListener('change', (e) => {
        let output = document.getElementById("bg-output");
        output.src = URL.createObjectURL(e.target.files[0]);
        output.onload = function() {
            URL.revokeObjectURL(output.src) // free memory
        }
    });
    document.querySelector('.bg button').addEventListener('click', (e) => {
        e.preventDefault();
        document.querySelector('.image-select input').files[0] = null;
        let output = document.getElementById("bg-output");
        output.src = 'static/user-default.png';
        output.onload = function() {
            URL.revokeObjectURL(output.src);
        }
    });
</script>