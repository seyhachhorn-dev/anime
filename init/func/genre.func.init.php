<?php

function getAllGenre(): array {
    global $conn;

    $stmt = $conn->prepare("SELECT id, name FROM genres");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


?>