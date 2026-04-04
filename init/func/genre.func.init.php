<?php

function getAllGenres(): array
{
    global $conn;

    $stmt = $conn->prepare("SELECT id, name FROM genres");
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getGenresPaginated(int $limit, int $offset): array
{
    global $conn;

    $stmt = $conn->prepare("SELECT id, name FROM genres ORDER BY id DESC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
function deleteGenre($id)
{
    global $conn;

    $query = $conn->prepare("DELETE FROM genres WHERE id = :id");
    return $query->execute([':id' => $id]);
}


?>