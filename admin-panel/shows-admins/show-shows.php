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

$totalShows = countShows();
$totalPages = (int) ceil($totalShows / $limit);

$shows = getAllShowsPaginated($limit, $offset);


?>

<div class="admin-page-head d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
        <h1 class="admin-page-head__title">Shows</h1>
        <p class="admin-page-head__sub">Manage series and movies in your catalog.</p>
    </div>
    <a href="<?php echo ADMINURL; ?>/shows-admins/create-shows.php" class="btn btn-admin-primary">Create show</a>
</div>

<div class="admin-card">
    <div class="admin-card__body p-0">
        <div class="table-responsive">
            <table class="table mb-0">
                <thead class="thead-light">
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Title</th>
                        <th scope="col">Image</th>
                        <th scope="col">Type</th>
                        <th scope="col">Date aired</th>
                        <th scope="col">Status</th>
                        <th scope="col">Genre</th>
                        <th scope="col">Available</th>
                        <th scope="col">Total</th>
                        <th scope="col">Created</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($shows)): ?>
                        <tr>
                            <td colspan="11" class="text-center text-muted py-4">No shows yet. Create one to get started.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($shows as $show): ?>
                            <tr>
                                <th scope="row"><?php echo (int) $show->id; ?></th>
                                <td><?php echo htmlspecialchars($show->title ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><img src="<?php echo APPURL; ?>/img/<?php echo htmlspecialchars($show->image ?? '', ENT_QUOTES, 'UTF-8'); ?>" alt="<?php echo htmlspecialchars($show->title ?? '', ENT_QUOTES, 'UTF-8'); ?>" style="width: 8rem; height: 8rem; object-fit: cover;"></td>
                                <td><?php echo htmlspecialchars($show->type ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($show->date_aired ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($show->status ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars($show->genre ?? '', ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars((string) ($show->num_avaliable ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?php echo htmlspecialchars((string) ($show->num_total ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><span class="text-nowrap"><?php echo htmlspecialchars($show->created_at ?? '', ENT_QUOTES, 'UTF-8'); ?></span></td>
                                <td>
                                    <button type="button" class="btn btn-sm btn-danger text-nowrap" onclick="confirmDelete(<?php echo (int) $show->id; ?>, '<?php echo htmlspecialchars($show->title ?? '', ENT_QUOTES, 'UTF-8'); ?>')">Delete <i class="bi bi-trash"></i></button>
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
function confirmDelete(id, title) {
    swal({
        title: "Are you sure?",
        text: `Do you really want to delete "${title}"? This action cannot be undone.`,
        icon: "warning",
        buttons: ["Cancel", "Delete"],
        dangerMode: true,
    }).then((willDelete) => {
        if (willDelete) {
            window.location.href = "<?php echo ADMINURL; ?>/shows-admins/delete-shows.php?id=" + id;
        }
    });
}

// Check for success message    
<?php if (isset($_GET['deleted']) && $_GET['status'] && $_GET['status'] == 'success'): ?>
swal({
    title: "Deleted!",
    text: "The show has been successfully deleted.",
    icon: "success",
    button: "OK",
});
window.history.replaceState({}, document.title, window.location.pathname);
<?php endif; ?>

<?php if (isset($_GET['created']) && $_GET['status'] && $_GET['status'] == 'success'): ?>
swal({
    title: "Created!",
    text: "The show has been successfully created.",
    icon: "success",
    button: "OK",
});
window.history.replaceState({}, document.title, window.location.pathname);
<?php endif; ?>
</script>

<?php require "../layout/footer.php"; ?>