<?php

require_once __DIR__ . "/../../init/init.php";

if (!isset($_SESSION['admin_username'])) {
    header("Location: " . ADMINURL . "/admins/login-admins.php");
    exit;
}

// Increase PHP limits for large file uploads
ini_set('upload_max_filesize', '100M');
ini_set('post_max_size', '100M');
ini_set('memory_limit', '256M');
ini_set('max_execution_time', '300');

$error = null;

$getAllShows = getAllShowsAdmin();

if (isset($_POST['submit'])) {
    $name = trim((string) ($_POST['name'] ?? ''));
    $showId = (int) ($_POST['show_id'] ?? 0);
    $episode_number = (int) ($_POST['episode_number'] ?? 1);

    if ($name === '' || $showId <= 0 || $episode_number <= 0) {
        $error = 'Please fill all required fields.';
    } else {
        $thumbnailName = '';
        if (!empty($_FILES['thumbnail']['name'])) {
            $uploaded = saveEpisodeThumbnailUpload($_FILES['thumbnail']);
            if ($uploaded === null) {
                $error = 'Thumbnail upload failed. Use jpg, png, gif, or webp under server limits.';
            } else {
                $thumbnailName = $uploaded;
            }
        }

        $videoName = '';
        if (!empty($_FILES['video']['name'])) {
            $uploaded = saveEpisodeVideoUpload($_FILES['video']);
            if ($uploaded === null) {
                $error = 'Video upload failed. Use mp4, avi, mkv, mov, or webm under server limits.';
            } else {
                $videoName = $uploaded;
            }
        }

        if ($error === null) {
            $data = [
                'episode_number' => $episode_number,
                'name' => $name,
                'thumbnail' => $thumbnailName,
                'video' => $videoName,
                'show_id' => $showId,
            ];

            if (createEpisode($data)) {
                header("Location: " . ADMINURL . "/episodes-admins/show-episodes.php?created&status=success");
                exit;
            }
            $error = 'Could not save episode. Please try again.';
        }
    }
}

require "../layout/header.php";

?>

<?php if ($error): ?>
    <script>
    swal({
        title: "Error!",
        text: <?php echo json_encode($error); ?>,
        icon: "error",
        button: "OK",
    });
    </script>
<?php endif; ?>

<div class="admin-page-head d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="admin-page-head__title">Create episode</h1>
        <p class="admin-page-head__sub">Add a new episode to a show.</p>
    </div>
    <a href="<?php echo ADMINURL; ?>/episodes-admins/show-episodes.php" class="btn btn-outline-secondary">Back to list</a>
</div>

<div class="admin-card">
    <div class="admin-card__body">
        <form method="POST" action="" enctype="multipart/form-data">
               <div class="form-group">
                <label class="small text-muted font-weight-bold" for="episode-name">Name</label>
                <input type="text" name="name" id="episode-name" class="form-control" placeholder="Episode name" required value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="episode-number">Episode Number</label>
                <input type="number" name="episode_number" id="episode-number" class="form-control" placeholder="Episode number" required value="<?php echo htmlspecialchars($_POST['episode_number'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="episode-thumbnail">Thumbnail</label>
                <input type="file" name="thumbnail" id="episode-thumbnail" class="form-control-file" accept=".jpg,.jpeg,.png,.gif,.webp,image/*">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="episode-video">Video</label>
                <input type="file" name="video" id="episode-video" class="form-control-file" accept=".mp4,.avi,.mkv,.mov,.webm,video/*">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="episode-show">Show</label>
                <select name="show_id" id="episode-show" class="form-control" required>
                    <option value="" disabled <?php echo empty($_POST['show_id']) ? 'selected' : ''; ?>>Choose show</option>
                    <?php foreach ($getAllShows as $show): ?>
                        <option value="<?= htmlspecialchars($show->id); ?>"
                            <?= (($_POST['show_id'] ?? '') == $show->id) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($show->title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</div>

<?php require "../layout/footer.php"; ?>