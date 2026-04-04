<?php

require_once __DIR__ . "/../../init/init.php";

if (!isset($_SESSION['admin_username'])) {
    header("Location: " . ADMINURL . "/admins/login-admins.php");
    exit;
}

$error = null;

if (isset($_POST['submit'])) {
    $name = trim((string) ($_POST['name'] ?? ''));
    if ($name === '') {
        $error = 'Please enter a genre name.';
    } elseif (createGenre($name)) {
        header("Location: " . ADMINURL . "/genres-admins/show-genres.php?created&status=success");
        exit;
    } else {
        $error = 'Could not create genre. It may already exist.';
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
        <h1 class="admin-page-head__title">Create genre</h1>
        <p class="admin-page-head__sub">Add a category for shows and navigation.</p>
    </div>
    <a href="<?php echo ADMINURL; ?>/genres-admins/show-genres.php" class="btn btn-outline-secondary">Back to list</a>
</div>

<div class="admin-card">
    <div class="admin-card__body">
        <form method="POST" action="">
            <div class="form-group">
                <label class="small text-muted font-weight-bold" for="genre-name">Name</label>
                <input type="text" name="name" id="genre-name" class="form-control" placeholder="e.g. Action" required maxlength="200" value="<?php echo htmlspecialchars($_POST['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Create</button>
        </form>
    </div>
</div>

<?php require "../layout/footer.php"; ?>