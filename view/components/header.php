<header>
    <nav class="smallnav">
        
    </nav>
    <nav class="bignav">
        <!-- use space-between
        (left and right links) -->
        <div>
            <a href="<?php echo $this->dir; ?>">Главная</a>
            <input id="dropdown" type="checkbox" hidden>
            <label for="dropdown">
                Статьи
                <div id="custom_pages">
                    <?php
                    if(isset($_SESSION['user_id'])){
                        $stmt = $this->pdo->query('SELECT id, header FROM posts WHERE pinned = 1 and author = '.$_SESSION['user_id']);
                        $pins = $stmt->fetchAll();
                        if($pins){
                            foreach ($pins as $pin) {
                                echo '<a href="blog?id='.$pin['id'].'">'.($pin['header'] != '' ? substr($pin['header'], 0, 30) : 'Пост '.$pin['id']).'</a>';
                            }
                        }
                        else{
                            echo '<p>Нет закрепов</p>';
                        }
                    }
                    else{
                        echo '<a href="auth">Авторизуйтесь для закрепления</a>';
                    }
                    ?>
                </div>
                <div class="arrow_down"></div>
            </label>
            
            <a href="about">Обо мне</a>
            <a href="advertisement">Реклама</a>
        </div>
        <div>
            <a href="profile">Профиль</a>
        </div>
    </nav>
    <input type="text" id="header_searchbar" placeholder="Поиск по блогу">
</header>