<?php
require_once '../../init/init.php';

if(isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    if ($id > 0) {
        deleteEpisode($id);
        header("Location: " . ADMINURL . "/episodes-admins/show-episodes.php?deleted&status=success");
        exit;
    }
}

?>