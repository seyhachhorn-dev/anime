<?php
require_once '../../init/init.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    if ($id > 0) {
        deleteShow($id);
        header("Location: " . ADMINURL . "/shows-admins/show-shows.php?deleted&status=success");
        exit;
    }
}