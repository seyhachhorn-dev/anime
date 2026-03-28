<?php


function getEpisodeInfoByShowIdAndEpId(int $show_id, int $ep_id): ?object
{
    global $conn;

    $query = $conn->prepare("
        SELECT * 
        FROM episode 
        WHERE show_id = :showId AND episode_number = :epId
    ");

    $query->bindValue(':showId', $show_id, PDO::PARAM_INT);
    $query->bindValue(':epId', $ep_id, PDO::PARAM_INT);

    $query->execute();

    $result = $query->fetch(PDO::FETCH_OBJ);

    return $result ?: null;
}


function getEpisodesByShowId(int $show_id): array
{
    global $conn;

    $query = $conn->prepare("
        SELECT * 
        FROM episode 
        WHERE show_id = :showId
    ");

    $query->bindValue(':showId', $show_id, PDO::PARAM_INT);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_OBJ); 
}
