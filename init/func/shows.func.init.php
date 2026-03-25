<?php

function getHeroShows(int $limit = 3): array
{
    global $conn;

    $stmt = $conn->prepare("SELECT * FROM shows LIMIT :limit");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getTrendingShows(int $limit = 0): array
{
    global $conn;

    $sql = "SELECT
                shows.id as id,
                shows.title as title,
                shows.type as type,
                shows.genre as genre,
                shows.image as image,
                shows.num_avaliable as num_avaliable,
                shows.num_total as num_total,
                COUNT(views.show_id) as view_count
            FROM shows
            JOIN views ON shows.id = views.show_id
            GROUP BY (shows.id)
            ORDER BY views.show_id ASC";

    if ($limit > 0) {
        $sql .= " LIMIT :limit";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    } else {
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getShowsByGenre(string $genreLike, int $limit = 0, string $orderBy = "views.show_id DESC"): array
{
    global $conn;

    // Whitelist allowed order by to avoid SQL injection.
    $allowedOrderBy = [
        "views.show_id DESC",
        "views.show_id ASC",
        "shows.created_at DESC",
        "shows.created_at ASC",
        "shows.id DESC",
        "shows.id ASC",
    ];
    if (!in_array($orderBy, $allowedOrderBy, true)) {
        $orderBy = "views.show_id DESC";
    }

    $sql = "SELECT
                shows.id as id,
                shows.title as title,
                shows.type as type,
                shows.genre as genre,
                shows.image as image,
                shows.num_avaliable as num_avaliable,
                shows.num_total as num_total,
                shows.created_at as created_at,
                COUNT(views.show_id) as view_count
            FROM shows
            LEFT JOIN views ON shows.id = views.show_id
            WHERE shows.genre LIKE :genre
            GROUP BY (shows.id)
            ORDER BY {$orderBy}";

    if ($limit > 0) {
        $sql .= " LIMIT :limit";
    }

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':genre', '%' . $genreLike . '%', PDO::PARAM_STR);
    if ($limit > 0) {
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getRecentlyAddedShows(int $limit = 0): array
{
    global $conn;

    $sql = "SELECT
                shows.id as id,
                shows.title as title,
                shows.type as type,
                shows.genre as genre,
                shows.image as image,
                shows.num_avaliable as num_avaliable,
                shows.num_total as num_total,
                shows.created_at as created_at,
                COUNT(views.show_id) as view_count
            FROM shows
            LEFT JOIN views ON shows.id = views.show_id
            GROUP BY (shows.id)
            ORDER BY shows.created_at DESC";

    if ($limit > 0) {
        $sql .= " LIMIT :limit";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    } else {
        $stmt = $conn->prepare($sql);
    }

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getForYouShows(int $limit = 6): array
{
    global $conn;

    $userId = getCurrentUserId();

    if (!$userId) {
        return getTrendingShows($limit);
    }

    $sql = "
        SELECT
            s.id,
            s.title,
            s.type,
            s.genre,
            s.image,
            s.num_avaliable,
            s.num_total,
            s.created_at,
            COUNT(v.show_id) AS view_count
        FROM shows s
        LEFT JOIN views v ON s.id = v.show_id
        WHERE s.id NOT IN (
            SELECT show_id
            FROM views
            WHERE user_id = :user_id
        )
        GROUP BY
            s.id, s.title, s.type, s.genre, s.image,
            s.num_avaliable, s.num_total, s.created_at
        ORDER BY view_count DESC, s.created_at DESC
        LIMIT :limit
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindValue(':user_id', $userId, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);

    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}
