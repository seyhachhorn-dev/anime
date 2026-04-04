<?php



require_once __DIR__ . "/../../init/init.php";

require "../layout/header.php";





$admins = getAllAdmins();



?>



                <div class="admin-page-head d-flex flex-wrap align-items-center justify-content-between gap-3">

                    <div>

                        <h1 class="admin-page-head__title">Admins</h1>

                        <p class="admin-page-head__sub">Manage administrator accounts.</p>

                    </div>

                    <a href="<?php echo ADMINURL; ?>/admins/create-admins.php" class="btn btn-admin-primary">Create admin</a>

                </div>



                <div class="admin-card">

                    <div class="admin-card__body p-0">

                        <div class="table-responsive">

                            <table class="table mb-0">

                                <thead class="thead-light">

                                    <tr>

                                        <th scope="col">#</th>

                                        <th scope="col">Username</th>

                                        <th scope="col">Email</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php foreach($admins as $admin): ?>

                                    <tr>

                                        <th scope="row"><?php echo $admin['id']; ?></th>

                                        <td><?php echo htmlspecialchars($admin['username'], ENT_QUOTES, 'UTF-8'); ?></td>

                                        <td><?php echo htmlspecialchars($admin['email'], ENT_QUOTES, 'UTF-8'); ?></td>

                                    </tr>

                                    <?php endforeach; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>



<script>
<?php if (isset($_GET['created']) && $_GET['status'] && $_GET['status'] == 'success'): ?>
swal({
    title: "Created!",
    text: "The admin has been successfully created.",
    icon: "success",
    button: "OK",
});
window.history.replaceState({}, document.title, window.location.pathname);
<?php endif; ?>
</script>

      <?php require "../layout/footer.php" ?>      

