<?php

interface CommentRepositoryInterface
{
    public function getCommentFile($idFile);

    public function addCommentFile($infoComment);
    
}