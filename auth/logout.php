<?php
require_once __DIR__ . "/../init/init.php";

session_unset();
session_destroy();

header("location: " . APPURL);
exit();
?>