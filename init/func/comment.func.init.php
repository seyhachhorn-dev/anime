<?php


function getAllCommentsByShowId($showId)
{

    global $conn;
    $query = $conn->prepare("SELECT * FROM comments Where show_id = :showId");
    $query->bindValue('showId', $showId, PDO::PARAM_INT);
    $query->execute();
    $result = $query->fetchAll(PDO::FETCH_OBJ);
    return $result ?: [];
}
