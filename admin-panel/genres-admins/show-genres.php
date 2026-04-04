<?php

require_once __DIR__ . "/../../init/init.php";

if (!isset($_SESSION['admin_username'])) {
    header("Location: " . ADMINURL . "/admins/login-admins.php");
    exit;
}

require "../layout/header.php";

$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$totalGenres = countGenres();
$totalPages = ceil($totalGenres / $limit);

$genres = getGenresPaginated($limit, $offset);

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
                                    <button type="button" class="btn btn-sm btn-danger text-nowrap" onclick="confirmDelete(<?php echo (int) $genre->id; ?>, '<?php echo htmlspecialchars($genre->name ?? '', ENT_QUOTES, 'UTF-8'); ?>')">Delete <i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>


            <?php if ($totalPages > 1): ?>
                <div class="p-3 d-flex justify-content-center">
                    <nav>
                        <ul class="pagination d-flex gap-2 mb-0">
                            <?php if ($page > 1): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                                </li>
                            <?php endif; ?>

                            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>  ">
                                    <a class="page-link" href="?page=<?php echo $i; ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>

                            <?php if ($page < $totalPages): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    function confirmDelete(id, title) {
        swal({
            title: "Are you sure?",
            text: `Do you really want to delete "${title}"? This action cannot be undone.`,
            icon: "warning",
            buttons: ["Cancel", "Delete"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                window.location.href = "<?php echo ADMINURL; ?>/genres-admins/delete.genres.php?id=" + id;
            }
        });
    }

    // Check for success message    
    <?php if (isset($_GET['deleted']) && $_GET['status'] && $_GET['status'] == 'success'): ?>
        swal({
            title: "Deleted!",
            text: "The genre has been successfully deleted.",
            icon: "success",
            button: "OK",
        });
        window.history.replaceState({}, document.title, window.location.pathname);
    <?php endif; ?>

    <?php if (isset($_GET['created']) && $_GET['status'] && $_GET['status'] == 'success'): ?>
        swal({
            title: "Created!",
            text: "The genre has been successfully created.",
            icon: "success",
            button: "OK",
        });
        window.history.replaceState({}, document.title, window.location.pathname);
    <?php endif; ?>
</script>

<?php require "../layout/footer.php"; ?>