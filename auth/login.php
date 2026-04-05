<?php
require_once __DIR__ . "/../init/init.php";

requireGuest();

$error = null;
$success = false;

if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error = "One or more inputs are empty!";
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = loginUser($email, $password);

        if ($user) {
            $_SESSION['id'] = (int)$user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            $success = true;
        } else {
            $error = "Email or password is wrong!";
        }
    }
}

require "../includes/header.php";
?>

<section class="normal-breadcrumb set-bg" data-setbg="<?php echo APPURL; ?>/img/normal-breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="normal__breadcrumb__text">
                    <h2>Login</h2>
                    <p>Welcome to the official Anime blog.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="login spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="login__form">
                    <h3>Login</h3>
                    <form action="login.php" method="POST">
                        <div class="input__item">
                            <input name="email" type="text" placeholder="Email address">
                            <span class="icon_mail"></span>
                        </div>
                        <div class="input__item">
                            <input name="password" type="password" placeholder="Password">
                            <span class="icon_lock"></span>
                        </div>
                        <button name="submit" type="submit" class="site-btn">Login Now</button>
                    </form>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="login__register">
                    <h3>Don’t Have An Account?</h3>
                    <a href="signup.php" class="primary-btn">Register Now</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php require "../includes/footer.php"; ?>

<?php if ($error): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: <?php echo json_encode($error); ?>,
        confirmButtonText: 'OK'
    });
});
</script>
<?php endif; ?>

<?php if ($success): ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    Swal.fire({
        icon: 'success',
        title: 'Login Successful',
        text: 'Welcome back!',
        timer: 1500,
        showConfirmButton: false
    }).then(() => {
        window.location.href = '<?php echo APPURL; ?>';
    });
});
</script>
<?php endif; ?>