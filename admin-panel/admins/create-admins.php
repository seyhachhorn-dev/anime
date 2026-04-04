<?php



require_once __DIR__ . "/../../init/init.php";


$error = null;
if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password'])) {
        $error = "one or no more input are empty!";
    } else {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $created = createAdmin($email, $username, $passwordHash, "admin");
        if ($created) {
            header("location: admins.php");
            exit();
        }

        $error = "Could not create admin. Please try again.";
    }
}

?>

<!-- login backend -->
<?php if ($error) : ?>
    <script>
        alert(<?php echo json_encode($error); ?>);
    </script>
<?php endif; ?>



?>
<?php require "../layout/header.php"; ?>

                <div class="admin-page-head">

                    <h1 class="admin-page-head__title">Create admin</h1>

                    <p class="admin-page-head__sub">Add a new administrator to the panel.</p>

                </div>



                <div class="admin-card">

                    <div class="admin-card__body">

                        <form method="POST" action="create-admins.php" enctype="multipart/form-data">

                            <div class="form-group">

                                <label class="small text-muted font-weight-bold" for="create-email">Email</label>

                                <input type="email" name="email" id="create-email" class="form-control" placeholder="Email" required>

                            </div>

                            <div class="form-group">

                                <label class="small text-muted font-weight-bold" for="create-username">Username</label>

                                <input type="text" name="username" id="create-username" class="form-control" placeholder="Username" required>

                            </div>

                            <div class="form-group">

                                <label class="small text-muted font-weight-bold" for="create-password">Password</label>

                                <input type="password" name="password" id="create-password" class="form-control" placeholder="Password" required>

                            </div>

                            <button type="submit" name="submit" class="btn btn-primary">Create</button>

                        </form>

                    </div>

                </div>

      <?php require "../layout/footer.php" ?>      

