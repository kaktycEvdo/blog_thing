<?php
class PinPostPage extends Page{
    function displayContent(){
        $post_id = $_GET['id'];

        $pdo = $this->pdo;
        $modal = new ServerModal();

        $u = unserialize($_SESSION['user']);
        if(!$u){
            $modal->throwModal('Ошибка: пользователь не авторизован', true, 'auth');
        }
        else{
            $uid = $u->id;
            $postQ = $pdo->prepare("SELECT pinned FROM posts WHERE id = :id and author = $uid");
            $postQ->bindParam('id', $post_id);
            $postQ->execute();
            $post = $postQ->fetch();

            if($post[0] == 1){
                $pinQ = $pdo->prepare("UPDATE posts SET pinned = 0 WHERE id = :id and author = $uid");
                $pinQ->bindParam('id', $post_id);
                $pinQ->execute();

                $modal->throwModal('Пост убран из закрепленных', false, 'about');
            }
            else if ($post[0] == 0){
                $pinQ = $pdo->prepare("UPDATE posts SET pinned = 1 WHERE id = :id and author = $uid");
                $pinQ->bindParam('id', $post_id);
                $pinQ->execute();

                $modal->throwModal('Пост закреплен', false, 'about');
            }
            else{
                $modal->throwModal('Поста не существует', true, 'about');
            }
        }
    }
}
$cur_page = new PinPostPage($pdo);