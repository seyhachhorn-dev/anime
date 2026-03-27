<?php

function insertFollow(int $showId, int $userId): bool
{
    global $conn;

    $query = $conn->prepare("
        INSERT INTO following (show_id, user_id) 
        VALUES (:showId, :userId)
    ");

    return $query->execute([
        ':showId' => $showId,
        ':userId' => $userId
    ]);
}


function checkFollowed(int $showId): bool
{
    global $conn;

    $userId = getCurrentUserId();

    if (!$userId) return false;

    $query = $conn->prepare("
        SELECT 1 
        FROM following 
        WHERE show_id = :showId AND user_id = :userId
        LIMIT 1
    ");

    $query->bindValue(':showId', $showId, PDO::PARAM_INT);
    $query->bindValue(':userId', $userId, PDO::PARAM_INT);

    $query->execute();

    return $query->fetch() ? true : false;
}

?>