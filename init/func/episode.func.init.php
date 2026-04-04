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



function countEpisodes(): int
{
    global $conn;
    $query = $conn->prepare("SELECT COUNT(*) FROM episode");
    $query->execute();
    return $query->fetchColumn();
}

function getAllEpisodesAdmin(): array
{
    global $conn;
    $stmt = $conn->query("SELECT episode.*, shows.title as show_title FROM episode JOIN shows ON episode.show_id = shows.id ORDER BY episode.id DESC");
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function getAllEpisodesPaginated(int $limit, int $offset): array
{
    global $conn;

    $stmt = $conn->prepare("
        SELECT episode.*, shows.title as show_title
        FROM episode
        JOIN shows ON episode.show_id = shows.id
        ORDER BY episode.id DESC
        LIMIT :limit OFFSET :offset
    ");

    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

function createEpisode(array $data): bool
{
    global $conn;
    
    $episode_number	= (int) ($data['episode_number'] ?? 1);
    $name = trim((string) ($data['name'] ?? ''));
    $thumbnail = trim((string) ($data['thumbnail'] ?? ''));
    $video = trim((string) ($data['video'] ?? ''));
    $showId = (int) ($data['show_id'] ?? 0);

    if ($name === '' || $showId <= 0) {
        return false;
    }

    $sql = 'INSERT INTO episode (episode_number, name, thumbnail, video, show_id) VALUES (:episode_number, :name, :thumbnail, :video, :show_id)';

    $stmt = $conn->prepare($sql);

    return $stmt->execute([
        ':episode_number' => $episode_number,
        ':name' => $name,
        ':thumbnail' => $thumbnail,
        ':video' => $video,
        ':show_id' => $showId,
    ]);
}

function deleteEpisode($id)
{
    global $conn;

    // get video filename first
    $stmt = $conn->prepare("SELECT video FROM episode WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $episode = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($episode && !empty($episode['video'])) {
        $videoPath = __DIR__ . "/../../admin-panel/episodes-admins/videos/" . $episode['video'];

        if (file_exists($videoPath) && is_file($videoPath)) {
            unlink($videoPath);
        }
    }

    // delete database row
    $stmt = $conn->prepare("DELETE FROM episode WHERE id = :id");
    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    return $stmt->execute();
}

function saveEpisodeThumbnailUpload(array $file): ?string
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

    $basename = 'episode_thumb_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = $imgDir . DIRECTORY_SEPARATOR . $basename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return null;
    }

    return $basename;
}


function saveEpisodeVideoUpload(array $file): ?string
{
    $err = (int) ($file['error'] ?? UPLOAD_ERR_NO_FILE);
    if ($err === UPLOAD_ERR_NO_FILE) {
        return null;
    }
    if ($err !== UPLOAD_ERR_OK || empty($file['tmp_name']) || !is_uploaded_file($file['tmp_name'])) {
        return null;
    }

    $ext = strtolower(pathinfo((string) ($file['name'] ?? ''), PATHINFO_EXTENSION));
    $allowed = ['mp4', 'avi', 'mkv', 'mov', 'webm'];
    if (!in_array($ext, $allowed, true)) {
        return null;
    }

    $root = realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '..');
    if ($root === false) {
        return null;
    }

    $videoDir = $root . DIRECTORY_SEPARATOR . 'admin-panel' . DIRECTORY_SEPARATOR . 'episodes-admins' . DIRECTORY_SEPARATOR . 'videos';
    if (!is_dir($videoDir) && !@mkdir($videoDir, 0755, true)) {
        return null;
    }

    $basename = 'episode_' . bin2hex(random_bytes(8)) . '.' . $ext;
    $dest = $videoDir . DIRECTORY_SEPARATOR . $basename;
    if (!move_uploaded_file($file['tmp_name'], $dest)) {
        return null;
    }

    return $basename;
}
