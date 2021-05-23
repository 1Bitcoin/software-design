<?php

require_once(ROOT . '/repository/comment/CommentRepositoryInterface.php');

use \RedBeanPHP\R as R;

class CommentRepository implements CommentRepositoryInterface
{
    public function __construct()
    {

    }
    
    public function getCommentFile($idFile)
    {
        $comments = R::getAll('SELECT comment.id, comment.content, comment.date_create, comment.raiting, user.name, user.raiting, 
                            role.name AS role_name FROM `comment` JOIN `user` ON comment.user_id = user.id JOIN `role` ON
                            role.id = user.role_id WHERE `file_id` = ?', [$idFile]);

        return $comments;
    }

    public function addCommentFile($infoComment)
    {
        $content = $infoComment['comment'];
        $user_id = $infoComment['user_id'];
        $file_id = $infoComment['file_id'];

        // Указываем, что будем работать с таблицей book
        $comment = R::dispense('comment');

        // Заполняем объект свойствами
        $comment->user_id = $user_id;
        $comment->file_id = $file_id;
        $comment->content = $content;

        // Сохраняем объект
        R::store($comment); 
        
        // Вернуть id добавленной записи
        return $comment->id;
    }

    public function deleteComment($infoComment)
    {
        $idComment = $infoComment['comment_id'];
        R::trashBatch('comment', [$idComment]);

        return 0;
    }
}