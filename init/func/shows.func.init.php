<?php

function deleteShow($id)
{
    global $conn;

    // 1. Get image filename from DB
    $stmt = $conn->prepare("SELECT image FROM shows WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $show = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($show && !empty($show['image'])) {

        // 2. Build full path to image
        $imagePath = __DIR__ . "/../../img/" . $show['image'];

        // 3. Delete file if exists
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    // 4. Delete DB record
    $query = $conn->prepare("DELETE FROM shows WHERE id = :id");
    return $query->execute([':id' => $id]);
}
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
                genres.name as genre,
                shows.image as image,
                shows.num_avaliable as num_avaliable,
                shows.num_total as num_total,
                COUNT(views.show_id) as view_count
            FROM shows
            JOIN views ON shows.id = views.show_id
            LEFT JOIN genres ON shows.genre = genres.id
            GROUP BY shows.id
            ORDER BY view_count DESC";

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

function getShowsByGenres(string $name): array
{
    global $conn;

    $query = $conn->prepare("
        SELECT * 
        FROM shows 
        WHERE genre LIKE :genre
    ");

    $query->bindValue(':genre', '%' . $name . '%', PDO::PARAM_STR);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_OBJ);
}


function getShowDetailById(int $showId): ?object
{
    global $conn;

    $query = $conn->prepare("
        SELECT
            s.id,
            s.title,
            s.type,
            s.genre,
            s.description,
            s.studios AS studio,
            s.date_aired,
            s.status,
            s.duration,
            s.quality,
            s.image,
            s.num_avaliable,
            s.num_total,
            s.created_at,
            COUNT(v.show_id) AS view_count
        FROM shows s
        LEFT JOIN views v ON s.id = v.show_id
        WHERE s.id = :id
        GROUP BY
            s.id, s.title, s.type, s.genre, s.studios,
            s.date_aired, s.status, s.duration, s.quality,
            s.image, s.num_avaliable, s.num_total, s.created_at
    ");

    $query->bindValue(':id', $showId, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_OBJ);

    return $result ?: null;
}


function getCustomInfoShowById(int $showId): ?object
{
    global $conn;

    $query = $conn->prepare("
        SELECT
            s.id,
            s.title,
            s.type,
            s.genre
        FROM shows s
        WHERE s.id = :id
        LIMIT 1
    ");

    $query->bindValue(':id', $showId, PDO::PARAM_INT);
    $query->execute();

    $result = $query->fetch(PDO::FETCH_OBJ);

    return $result ?: null;
}


function insertViewForEachShow(int $show_id, int $user_id)
{
    global $conn;

    $query = $conn->prepare("SELECT * FROM views Where show_id = :showId and user_id = :userId");

    $query->bindValue(':showId', $show_id, PDO::PARAM_INT);
    $query->bindValue(':userId', $user_id, PDO::PARAM_INT);

    $query->execute();

    if ($query->rowCount() === 0) {

        $query = $conn->prepare("INSERT INTO views (show_id,user_id ) VALUES (:showId,:userId)");
        return $query->execute([
            ":showId" => $show_id,
            ":userId" => $user_id
        ]);
    } else {
        return null;
    }
}




function getFollowedShows(): array
{
    global $conn;

    $user_id = getCurrentUserId();

    if ($user_id === null) {
        return [];
    }

    $query = $conn->prepare("
        SELECT 
            shows.id AS show_id,
            shows.title AS title,
            shows.image AS image,
            shows.type AS type,
            shows.genre AS genre,
            shows.num_avaliable AS num_avaliable,
            shows.num_total AS num_total
        FROM shows
        INNER JOIN following 
            ON shows.id = following.show_id
        WHERE following.user_id = :userId
    ");

    $query->bindValue(':userId', $user_id, PDO::PARAM_INT);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_OBJ);
}



function getShowsBySearch(string $keyword): array
{
    global $conn;

    $query = $conn->prepare("
        SELECT *
        FROM shows
        WHERE title LIKE :keyword
           OR genre LIKE :keyword
           OR type LIKE :keyword
           OR studios LIKE :keyword
    ");

    $query->bindValue(':keyword', '%' . trim($keyword) . '%', PDO::PARAM_STR);
    $query->execute();

    return $query->fetchAll(PDO::FETCH_OBJ);
}



function countShows(): int
{
    global $conn;
    $query = $conn->prepare("SELECT COUNT(*) FROM shows");
    $query->execute();
    return $query->fetchColumn();
}

/** All rows for admin panel listing (newest first). */
function getAllShowsAdmin(): array
{
    global $conn;
    $stmt = $conn->query("SELECT * FROM shows ORDER BY id DESC");
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getAllShowsPaginated(int $limit, int $offset): array
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT *
        FROM shows
        ORDER BY id DESC
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}



function countGenres(): int
{
    global $conn;
    $query = $conn->prepare("SELECT COUNT(*) FROM genres");
    $query->execute();
    return $query->fetchColumn();
}



/**
 * Save an uploaded cover image to the site /img/ folder. Returns the stored filename, or null on skip/failure.
 */
function saveShowImageUpload(array $file): ?string
{
    $err = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($err === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($err !== UPLOAD_ERR_OK || empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return null;
    }

    $ext = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    if (!in_array($ext, $allowed, true)) {
        return null;
    }

    $root = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
    if ($root === false) {
        return null;
    }

    $imgDir = $root . DIRECTORY_SEPARATOR . 'img';
    if (!is_dir($imgDir) && !@mkdir($imgDir, 0755, true)) {
        return null;
    }

    $basename = 'show_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = $imgDir . DIRECTORY_SEPARATOR . $basename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return null;
    }

    return $basename;
}


function createShow(array $data): bool
{
    global $conn;

    $title = trim((string) ($data['title'] ?? ''));
    $image = trim((string) ($data['image'] ?? ''));
    $description = trim((string) ($data['description'] ?? ''));
    $type = trim((string) ($data['type'] ?? ''));
    $studios = trim((string) ($data['studios'] ?? ''));
    $dateAired = trim((string) ($data['date_aired'] ?? ''));
    $status = trim((string) ($data['status'] ?? ''));
    $genre = trim((string) ($data['genre'] ?? ''));
    $duration = trim((string) ($data['duration'] ?? ''));
    $quality = trim((string) ($data['quality'] ?? ''));
    $numAvailable = (int) ($data['num_avaliable'] ?? 0);
    $numTotal = (int) ($data['num_total'] ?? 0);

    if ($title === '' || $description === '' || $type === '' || $genre === '') {
        return false;
    }

    $sql = 'INSERT INTO shows (
        title, image, description, type, studios, date_aired, status, genre,
        duration, quality, num_avaliable, num_total
    ) VALUES (
        :title, :image, :description, :type, :studios, :date_aired, :status, :genre,
        :duration, :quality, :num_avaliable, :num_total
    )';

    $stmt = $conn->prepare($sql);

    return $stmt->execute([
        ':title' => $title,
        ':image' => $image,
        ':description' => $description,
        ':type' => $type,
        ':studios' => $studios,
        ':date_aired' => $dateAired,
        ':status' => $status,
        ':genre' => $genre,
        ':duration' => $duration,
        ':quality' => $quality,
        ':num_avaliable' => $numAvailable,
        ':num_total' => $numTotal,
    ]);
}

?>