<?php
require_once __DIR__ . "/../init/init.php";

requireGuest();

$error = null;
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

            header("location: " . APPURL);
            exit();
        }

        $error = "Email or password is wrong!";
    }
}

require "../includes/header.php";
?>

<!-- login backed -->
<?php if ($error) : ?>
    <script>
        alert(<?php echo json_encode($error); ?>);
    </script>
<?php endif; ?>


<!-- Normal Breadcrumb Begin -->
<section class="normal-breadcrumb set-bg" data-setbg="<?php echo APPURL; ?>/img/normal-breadcrumb.jpg">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="normal__breadcrumb__text">
                    <h2>Login</h2>
                    <p>Welcome to the official Anime blog.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Normal Breadcrumb End -->

<!-- Login Section Begin -->
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
                    <!-- <a href="#" class="forget_pass">Forgot Your Password?</a> -->
                </div>
            </div>
            <div class="col-l   g-6">
                <div class="login__register">
                    <h3>Dont’t Have An Account?</h3>
                    <a href="signup.php" class="primary-btn">Register Now</a>
                </div>
            </div>
        </div>

    </div>
</section>
<?php require "../includes/footer.php"; ?>
