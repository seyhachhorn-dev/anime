<?php
require_once __DIR__ . "/../init/init.php";

requireGuest();

$error = null;
$success = false;
if (isset($_POST['submit'])) {
    if (empty($_POST['email']) || empty($_POST['username']) || empty($_POST['password'])) {
        $error = "one or no more input are empty!";
    } else {
        $email = $_POST['email'];
        $username = $_POST['username'];
        $passwordHash = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $created = createUser($email, $username, $passwordHash);
        if ($created) {
            $success = true;
        } else {
            $error = "Could not create user. Please try again.";
        }
    }
}

require "../includes/header.php";
?>

<!-- login backend -->
<?php if ($error) : ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: 'Sign Up Failed',
            text: <?php echo json_encode($error); ?>,
            confirmButtonText: 'OK'
        });
    </script>
<?php endif; ?>

<?php if ($success) : ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: 'Sign Up Successful',
            text: 'Account created! Redirecting to login...',
            timer: 2000,
            showConfirmButton: false
        }).then(() => {
            window.location.href = 'login.php';
        });
    </script>
<?php endif; ?>


    <!-- Normal Breadcrumb Begin -->
    <section class="normal-breadcrumb set-bg" data-setbg="<?php echo APPURL; ?>/img/normal-breadcrumb.jpg">
        <div class="container">
            <div class="row">
                <div class="col-lg-12 text-center">
                    <div class="normal__breadcrumb__text">
                        <h2>Sign Up</h2>
                        <p>Welcome to the official Anime blog.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Normal Breadcrumb End -->

    <!-- Signup Section Begin -->
    <section class="signup spad">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="login__form">
                        <h3>Sign Up</h3>
                        <form action="signup.php" method="POST">
                            <div class="input__item ">
                                <input name="email" class="col-md-12" type="text" placeholder="Email address">
                                <span class="icon_mail"></span>
                            </div>
                            <div class="input__item">
                                <input name="username" type="text" placeholder="Your Name">
                                <span class="icon_profile"></span>
                            </div>
                            <div class="input__item">
                                <input name="password" type="password" placeholder="Password">
                                <span class="icon_lock"></span>
                            </div>
                            <button type="submit" name="submit" class="site-btn">Register Now</button>
                        </form>
                        <h5>Already have an account? <a href="login.php">Log In!</a></h5>
                    </div>
                </div>
               
            </div>
        </div>
    </section>
    <!-- Signup Section End -->

<?php require "../includes/footer.php"; ?>
