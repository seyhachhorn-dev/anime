<?php
require_once __DIR__ . "/../../init/init.php";
require "../layout/header.php";

if(isset($_SESSION['admin_username'])) {
    header("Location: " . ADMINURL);
    exit;
}
$error = null;

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

            header("Location: " . ADMINURL);
            exit;
        } else {
            $error = "Email or password is wrong!";
        }
    }
}
?>

<?php if ($error): ?>
<script>
    alert(<?php echo json_encode($error); ?>);
</script>
<?php endif; ?>

      <div class="row">
        <div class="col">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title mt-5">Login</h5>
              <form method="POST" class="p-auto" action="login-admins.php">
                  <!-- Email input -->
                  <div class="form-outline mb-4">
                    <input type="email" name="email" id="form2Example1" class="form-control" placeholder="Email" />
                   
                  </div>

                  
                  <!-- Password input -->
                  <div class="form-outline mb-4">
                    <input type="password" name="password" id="form2Example2" placeholder="Password" class="form-control" />
                    
                  </div>



                  <!-- Submit button -->
                  <button type="submit" name="submit" class="btn btn-primary  mb-4 text-center">Login</button>

                 
                </form>

            </div>
       </div>
     </div>
    </div>
</div>
  <?php require "../layout/footer.php" ?>      
