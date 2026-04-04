<?php

require_once __DIR__ . "/../../init/init.php";

if (!isset($_SESSION['admin_username'])) {
    header("Location: " . ADMINURL . "/admins/login-admins.php");
    exit;
}

$error = null;

$getAllGenres = getAllGenres();

if (isset($_POST['submit'])) {
    $title = trim((string) ($_POST['title'] ?? ''));
    $description = trim((string) ($_POST['description'] ?? ''));
    $type = trim((string) ($_POST['type'] ?? ''));
    $genre = trim((string) ($_POST['genre'] ?? ''));

    if ($title === '' || $description === '' || $type === '' || $genre === '') {
        $error = 'Please fill title, description, type, and genre.';
    } else {
        $imageName = '';
        if (!empty($_FILES['image']['name'])) {
            $uploaded = saveShowImageUpload($_FILES['image']);
            if ($uploaded === null) {
                $error = 'Image upload failed. Use jpg, png, gif, or webp under server limits.';
            } else {
                $imageName = $uploaded;
            }
        }

        if ($error === null) {
            $data = [
                'title' => $title,
                'image' => $imageName,
                'description' => $description,
                'type' => $type,
                'studios' => trim((string) ($_POST['studios'] ?? '')),
                'date_aired' => trim((string) ($_POST['date_aired'] ?? '')),
                'status' => trim((string) ($_POST['status'] ?? '')),
                'genre' => $genre,
                'duration' => trim((string) ($_POST['duration'] ?? '')),
                'quality' => trim((string) ($_POST['quality'] ?? '')),
                'num_avaliable' => $_POST['num_avaliable'] ?? 0,
                'num_total' => $_POST['num_total'] ?? 0,
            ];

            if (createShow($data)) {
                header("Location: " . ADMINURL . "/shows-admins/show-shows.php");
                exit;
            }
            $error = 'Could not save show. Please try again.';
        }
    }
}

require "../layout/header.php";

?>

<?php if ($error): ?>
    <script>
        alert(<?php echo json_encode($error); ?>);
    </script>
<?php endif; ?>

<div class="admin-page-head d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="admin-page-head__title">Create show</h1>
        <p class="admin-page-head__sub">Add a new title to the catalog.</p>
    </div>
    <a href="<?php echo ADMINURL; ?>/shows-admins/show-shows.php" class="btn btn-outline-secondary">Back to list</a>
</div>

<div class="admin-card">
    <div class="admin-card__body">
        <form method="POST" action="" enctype="multipart/form-data">
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-title">Title</label>
                <input type="text" name="title" id="show-title" class="form-control" placeholder="Title" required value="<?php echo htmlspecialchars($_POST['title'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-image">Image</label>
                <input type="file" name="image" id="show-image" class="form-control-file" accept=".jpg,.jpeg,.png,.gif,.webp,image/*">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-description">Description</label>
                <textarea class="form-control" name="description" id="show-description" rows="4" placeholder="Synopsis" required><?php echo htmlspecialchars($_POST['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-type">Type</label>
                <select name="type" id="show-type" class="form-control" required>
                    <option value="" disabled <?php echo empty($_POST['type']) ? 'selected' : ''; ?>>Choose type</option>
                    <option value="Tv Series" <?php echo (($_POST['type'] ?? '') === 'Tv Series') ? 'selected' : ''; ?>>TV series</option>
                    <option value="Movie" <?php echo (($_POST['type'] ?? '') === 'Movie') ? 'selected' : ''; ?>>Movie</option>
                </select>
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-studios">Studios</label>
                <input type="text" name="studios" id="show-studios" class="form-control" placeholder="Studios" value="<?php echo htmlspecialchars($_POST['studios'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-date-aired">Date aired</label>
                <input type="text" name="date_aired" id="show-date-aired" class="form-control" placeholder="e.g. 2023-04-09" value="<?php echo htmlspecialchars($_POST['date_aired'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-status">Status</label>
                <input type="text" name="status" id="show-status" class="form-control" placeholder="e.g. Airing" value="<?php echo htmlspecialchars($_POST['status'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-genre">Genre</label>
                <select name="genre" id="show-genre" class="form-control" required>
                    <option value="" disabled <?= empty($_POST['genre']) ? 'selected' : ''; ?>>
                        Choose genre
                    </option>

                    <?php foreach ($getAllGenres as $genre): ?>
                        <option value="<?= htmlspecialchars($genre['name']); ?>"
                            <?= (($_POST['genre'] ?? '') == $genre['name']) ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($genre['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-duration">Duration</label>
                <input type="text" name="duration" id="show-duration" class="form-control" placeholder="e.g. 24 min" value="<?php echo htmlspecialchars($_POST['duration'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-quality">Quality</label>
                <input type="text" name="quality" id="show-quality" class="form-control" placeholder="e.g. HD" value="<?php echo htmlspecialchars($_POST['quality'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-num-available">Episodes available</label>
                <input type="text" name="num_avaliable" id="show-num-available" class="form-control" placeholder="Available count" inputmode="numeric" value="<?php echo htmlspecialchars($_POST['num_avaliable'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="show-num-total">Episodes total</label>
                <input type="text" name="num_total" id="show-num-total" class="form-control" placeholder="Total count" inputmode="numeric" value="<?php echo htmlspecialchars($_POST['num_total'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</div>

<?php require "../layout/footer.php"; ?>