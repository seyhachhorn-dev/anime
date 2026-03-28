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


// function insertComment(string $comment, int $show_id, int $user_id, string $user_name): ?bool
// {

//     global $conn;

//     $query = $conn->prepare("INSERT INTO comments (comment, show_id, user_id, user_name) VALUES (:cmt,:showId,:userId,:username)");
//     return $query->execute(
//         [
//             ":cmt" => $comment,
//             ":showId" => $show_id,
//             ":userId" => $user_id,
//             ":username" => $user_name
//         ]
//     );
// }

function insertComment(string $comment, int $show_id, int $user_id, string $user_name): bool
{
    global $conn;

    $query = $conn->prepare("
        INSERT INTO comments (comment, show_id, user_id, user_name)
        VALUES (:cmt, :showId, :userId, :username)
    ");

    return $query->execute([
        ':cmt' => $comment,
        ':showId' => $show_id,
        ':userId' => $user_id,
        ':username' => $user_name
    ]);
}
