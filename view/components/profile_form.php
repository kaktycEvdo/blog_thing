<div class="profile_page">
    <h1>Профиль</h1>
    <form method="POST">
        <input type="text" name="name" value="<?php echo $user_data['name'] ?>" placeholder="Имя профиля">
        <input type="email" name="email"  value="<?php echo $user_data['email'] ?>" placeholder="Электронная почта">
        <input type="password" name="newPassword" value="" placeholder="Новый пароль" autocomplete="new-password">
        <input type="password" name="repeatPassword" value="" placeholder="Подтвердите пароль" autocomplete="new-password">
        <div>
            <input type="submit" value="Сохранить">
            <button>Сбросить</button>
        </div>
    </form>
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
                <input accept="image/*" type="file" limit="20000" id="profile_image">
                <label for="profile_image">
                    Выбрать аватар
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAABA0lEQVR4nO3ZMQ7CMAyF4XccBphggrJT7n8COqByicdSIRQBTZo6tpF/yRtDvhihVgBRFJV2BTAAoNEZAPQ5EMsITnPLgdDJzKZ9QAYkae2bO6KuzspX61QJgRVI6TwA7LxDRgDb6Rxbr5D3TWwA3D1CPiHoDfINQU+QXwh6gcwh6AGSg6B1SC6CliElCFqFlCJoEbIEQWuQpQhagtQgaAWSPgCOC18BzpqQ2k2wYFtikBYISkNaISgJaYmgFKQ1ghIQDQQlIPvKn1gzkA7AoTGCEhCtmU37gAxIkvZNMzaSpH3TjI0kad80YyNJf7ORwcAhucbf0/30QcuISw4kiiK8egKYlVxgnFQcXAAAAABJRU5ErkJggg==">
                </label>
                <button class="other-button">
                    Сбросить
                </button>
            </div>
        </div>
        <div class="bg">
            <img id="bg-output" src="static/<?php echo $user_data['bg'] ?>" alt="Profile background">
            <div>
                <input accept="image/*" type="file" limit="20000" id="profile_bg">
                <label for="profile_bg">
                    Выбрать фон профиля
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADIAAAAyCAYAAAAeP4ixAAAACXBIWXMAAAsTAAALEwEAmpwYAAABA0lEQVR4nO3ZMQ7CMAyF4XccBphggrJT7n8COqByicdSIRQBTZo6tpF/yRtDvhihVgBRFJV2BTAAoNEZAPQ5EMsITnPLgdDJzKZ9QAYkae2bO6KuzspX61QJgRVI6TwA7LxDRgDb6Rxbr5D3TWwA3D1CPiHoDfINQU+QXwh6gcwh6AGSg6B1SC6CliElCFqFlCJoEbIEQWuQpQhagtQgaAWSPgCOC18BzpqQ2k2wYFtikBYISkNaISgJaYmgFKQ1ghIQDQQlIPvKn1gzkA7AoTGCEhCtmU37gAxIkvZNMzaSpH3TjI0kad80YyNJf7ORwcAhucbf0/30QcuISw4kiiK8egKYlVxgnFQcXAAAAABJRU5ErkJggg==">
                </label>
                <button class="other-button">
                    Сбросить
                </button>
            </div>
        </div>
    </div>
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