<?php

require_once __DIR__ . "/../../init/init.php";

if (!isset($_SESSION['admin_username'])) {
    header("Location: " . ADMINURL . "/admins/login-admins.php");
    exit;
}

require "../layout/header.php";

// $episodes = getAllEpisodesAdmin();

$limit = 5;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$offset = ($page - 1) * $limit;

$totalEpisodes = countEpisodes();
$totalPages = (int) ceil($totalEpisodes / $limit);

$episodes = getAllEpisodesPaginated($limit, $offset);

?>

<div class="admin-page-head d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="admin-page-head__title">Episodes</h1>
        <p class="admin-page-head__sub">Manage episodes for your shows.</p>
    </div>
    <a href="<?php echo ADMINURL; ?>/episodes-admins/create-episodes.php" class="btn btn-admin-primary">Create episode</a>
</div>

<div class="admin-card">
    <div class="admin-card__body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Thumbnail</th>
                        <th scope="col">Video</th>
                        <th scope="col">Show</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($episodes)): ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">No episodes yet. Create one to get started.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($episodes as $episode): ?>
                            <tr>
                                <th scope="row"><?php echo (int) $episode->id; ?></th>
                                <td><?php echo htmlspecialchars($episode->name ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <?php if (!empty($episode->thumbnail)): ?>
                                        <img src="<?php echo APPURL; ?>/img/<?php echo htmlspecialchars($episode->thumbnail, ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($episode->name ?? '', ENT_QUOTES, 'UTF-8'); ?>" style="width: 8rem; height: 8rem; object-fit: cover;">
                                    <?php else: ?>
                                        <span class="text-muted">No thumbnail</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($episode->video ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($episode->show_title ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger text-nowrap" onclick="confirmDelete(<?php echo (int) $episode->id; ?>, '<?php echo htmlspecialchars($episode->name ?? '', ENT_QUOTES, 'UTF-8'); ?>')">Delete <i class="bi bi-trash"></i>
                                    </button>
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
                                <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
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
    function confirmDelete(id, name) {
        swal({
            title: "Are you sure?",
            text: `Do you really want to delete "${name}"? This action cannot be undone.`,
            icon: "warning",
            buttons: ["Cancel", "Delete"],
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {
                window.location.href = "<?php echo ADMINURL; ?>/episodes-admins/delete-episodes.php?id=" + id;
            }
        });
    }

    // Check for success message
    <?php if (isset($_GET['deleted']) && isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        swal({
            title: "Deleted!",
            text: "The episode has been successfully deleted.",
            icon: "success",
            button: "OK",
        });
        window.history.replaceState({}, document.title, window.location.pathname);

    <?php endif; ?>

    <?php if (isset($_GET['created']) && isset($_GET['status']) && $_GET['status'] === 'success'): ?>
        swal({
            title: "Created!",
            text: "The episode has been successfully created.",
            icon: "success",
            button: "OK",
        });
        window.history.replaceState({}, document.title, window.location.pathname);
    <?php endif; ?>
</script>

<?php require "../layout/footer.php"; ?>