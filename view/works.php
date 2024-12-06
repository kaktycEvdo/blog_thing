<div class="padding_page">
<h3>Мои работы</h3>
<?php
if(sizeof($works) != 0){

}
else{
    echo '<h4>У пользователя нет работ</h4>';
}
// suid - session user id
if($user_id == $suid){
    echo '<a class="add_work" href="create_work">Добавить новую работу</a>';
}
?>
</div>