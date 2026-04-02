<?php
require_once __DIR__ . "/../../init/init.php";

unset($_SESSION['admin_id'], $_SESSION['admin_email'], $_SESSION['admin_username']);

header("location: " . ADMINURL . "/admins/login-admins.php");
exit();
?>      