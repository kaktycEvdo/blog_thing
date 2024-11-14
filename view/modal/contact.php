<div class="inner">
    <form method="POST" action="message?id=<?php echo $_SESSION['left_user_id']; ?>">
    <input type="text" placeholder="Ваше имя" >
    <input type="text" placeholder="Ваш email">
    <input type="text" placeholder="Текст сообщения">
    <input type="submit" value="Отправить">
    </form>
    <div>
        <a href="mail:info@mywebsite.ru">email: info@mywebsite.ru</a>
        <a href="tel:+943-232-856-22">тел: +943-232-856-22</a>
    </div>
</div>