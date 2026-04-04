<?php

require_once __DIR__ . "/../../init/init.php";

if (!isset($_SESSION['admin_username'])) {
    header("Location: " . ADMINURL . "/admins/login-admins.php");
    exit;
}

require "../layout/header.php";

?>

                <div class="admin-page-head d-flex flex-wrap align-items-center justify-content-between gap-3">
                    <div>
                        <h1 class="admin-page-head__title">Create show</h1>
                        <p class="admin-page-head__sub">Add a new title to the catalog.</p>
                    </div>
                    <a href="<?php echo ADMINURL; ?>/shows-admins/show-shows.php" class="btn btn-outline-secondary">Back to list</a>
                </div>

                <div class="admin-card">
                    <div class="admin-card__body">
                        <form method="POST" action="" enctype="multipart/form-data">
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-title">Title</label>
                                <input type="text" name="title" id="show-title" class="form-control" placeholder="Title" required>
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-image">Image</label>
                                <input type="file" name="image" id="show-image" class="form-control-file">
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-description">Description</label>
                                <textarea class="form-control" name="description" id="show-description" rows="4" placeholder="Synopsis"></textarea>
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-type">Type</label>
                                <select name="type" id="show-type" class="form-control">
                                    <option value="" selected disabled>Choose type</option>
                                    <option value="Tv Series">TV series</option>
                                    <option value="Movie">Movie</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-studios">Studios</label>
                                <input type="text" name="studios" id="show-studios" class="form-control" placeholder="Studios">
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-date-aired">Date aired</label>
                                <input type="text" name="date_aired" id="show-date-aired" class="form-control" placeholder="e.g. 2023-04-09">
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-status">Status</label>
                                <input type="text" name="status" id="show-status" class="form-control" placeholder="e.g. Airing">
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-genre">Genre</label>
                                <select name="genre" id="show-genre" class="form-control">
                                    <option value="" selected disabled>Choose genre</option>
                                    <option value="Magic">Magic</option>
                                    <option value="Action">Action</option>
                                    <option value="Adventure">Adventure</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-duration">Duration</label>
                                <input type="text" name="duration" id="show-duration" class="form-control" placeholder="e.g. 24 min">
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-quality">Quality</label>
                                <input type="text" name="quality" id="show-quality" class="form-control" placeholder="e.g. HD">
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-num-available">Episodes available</label>
                                <input type="text" name="num_avaliable" id="show-num-available" class="form-control" placeholder="Available count" inputmode="numeric">
                            </div>
                            <div class="form-group">
                                <label class="small text-muted font-weight-bold" for="show-num-total">Episodes total</label>
                                <input type="text" name="num_total" id="show-num-total" class="form-control" placeholder="Total count" inputmode="numeric">
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary">Create</button>
                        </form>
                    </div>
                </div>

<?php require "../layout/footer.php"; ?>
