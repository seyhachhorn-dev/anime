<?php

require_once __DIR__ . "/../../init/init.php";

if (!isset($_SESSION['admin_username'])) {
    header("Location: " . ADMINURL . "/admins/login-admins.php");
    exit;
}

require "../layout/header.php";

$genres = getAllGenresAdmin();

?>

<div class="admin-page-head d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="admin-page-head__title">Genres</h1>
        <p class="admin-page-head__sub">Categories used across the site navigation and browsing.</p>
    </div>
    <a href="<?php echo ADMINURL; ?>/genres-admins/create-genres.php" class="btn btn-admin-primary">Create genre</a>
</div>

<div class="admin-card">
    <div class="admin-card__body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($genres)): ?>
                        <tr>
                            <td colspan="3" class="text-center text-muted py-4">No genres yet. Create one to get started.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($genres as $genre): ?>
                            <tr>
                                <th scope="row"><?php echo (int) ($genre->id ?? 0); ?></th>
                                <td><?php echo htmlspecialchars($genre->name ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" disabled title="Not implemented yet">Delete</button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php require "../layout/footer.php"; ?>