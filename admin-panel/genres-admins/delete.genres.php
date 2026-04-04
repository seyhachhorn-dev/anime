<?php
require_once '../../init/init.php';

if (isset($_GET['id'])) {
    $id = (int) $_GET['id'];

    if ($id > 0) {
        deleteGenre($id);
        header("Location: " . ADMINURL . "/genres-admins/show-genres.php?deleted&status=success");
        exit;
    }
}