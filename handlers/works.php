<div class="padding_page">
<h3>Мои работы</h3>
<?php
$stmt = $mysql->prepare("SELECT * FROM works WHERE author = :id");
$user_id = $_GET['user'];
$stmt->bindParam('id', $user_id);
$stmt->execute();
$works = $stmt->fetchAll();
if(sizeof($works) != 0){

}
else{
    echo '<h4>У пользователя нет работ</h4>';
}
if($user_id == $_SESSION['user_id']){
    echo '<a class="add_work" href="create_work">Добавить новую работу</a>';
}
?>
</div>