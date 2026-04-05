<?php
require_once __DIR__ . "/../../init/init.php";

if(isset($_SESSION['admin_username'])) {
    header("Location: " . ADMINURL);
    exit;
}

require "../layout/header.php";

$error = null;
$success = false;

if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error = "One or more inputs are empty!";
    } else {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);

        $user = loginAdmin($email, $password);

        if ($user) {
            $_SESSION['admin_id'] = $user['id'];
            $_SESSION['admin_email'] = $user['email'];
            $_SESSION['admin_username'] = $user['username'];

            $success = true;
        } else {
            $error = "Email or password is wrong!";
        }
    }
}
?>

<?php if ($error): ?>
<script>
Swal.fire({
    icon: 'error',
    title: 'Login Failed',
    text: <?php echo json_encode($error); ?>,
    confirmButtonText: 'OK'
});
</script>
<?php endif; ?>

<?php if ($success): ?>
<script>
Swal.fire({
    icon: 'success',
    title: 'Login Successful',
    text: 'Welcome to Admin Panel!',
    timer: 2000,
    showConfirmButton: false
}).then(() => {
    window.location.href = '<?php echo ADMINURL; ?>';
});
</script>
<?php endif; ?>

                <h2 class="h5 font-weight-bold mb-4" style="color: var(--admin-text);">Sign in</h2>
                <form method="POST" action="login-admins.php">
                    <div class="form-group">
                        <label class="small text-muted font-weight-bold" for="login-email">Email</label>
                        <input type="email" name="email" id="login-email" class="form-control" placeholder="you@example.com" required>
                    </div>
                    <div class="form-group">
                        <label class="small text-muted font-weight-bold" for="login-password">Password</label>
                        <input type="password" name="password" id="login-password" class="form-control" placeholder="Password" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary btn-block py-2 font-weight-bold">Log in</button>
                </form>

  <?php require "../layout/footer.php" ?>      
