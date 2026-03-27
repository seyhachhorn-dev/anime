<?php

function getAllGenresCategories(): array
{
    global $conn;

    $query = $conn->prepare("SELECT * FROM genres");
    $query->execute();
    return $query->fetchAll(PDO::FETCH_OBJ);
}



?>