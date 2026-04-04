<?php

function getAllGenresCategories(): array
{
    global $conn;

    $query = $conn->prepare("SELECT * FROM genres ORDER BY name ASC");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}

/** Admin list (same data, explicit name for admin pages). */
function getAllGenresAdmin(): array
{
    return getAllGenresCategories();
}

function genreNameExists(string $name): bool
{
    global $conn;
    $stmt = $conn->prepare('SELECT COUNT(*) FROM genres WHERE name = :name');
    $stmt->execute([':name' => trim($name)]);
    return (int) $stmt->fetchColumn() > 0;
}

function createGenre(string $name): bool
{
    global $conn;
    $name = trim($name);
    if ($name === '') {
        return false;
    }
    if (genreNameExists($name)) {
        return false;
    }
    $stmt = $conn->prepare('INSERT INTO genres (name) VALUES (:name)');
    return $stmt->execute([':name' => $name]);
}

?>