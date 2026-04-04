
<?php require "layout/header.php" ?>   

<?php

if(!isset($_SESSION['admin_username'])) {
    header("Location: " . ADMINURL . "/admins/login-admins.php");
    exit;
}


$countShows = countShows();
$countEpisodes = countEpisodes();
$countGenres = countGenres();
$countAdmins = countAdmins();


?>
                <div class="admin-page-head">
                    <h1 class="admin-page-head__title">Dashboard</h1>
                    <p class="admin-page-head__sub">Overview of your anime catalog and team.</p>
                </div>

                <div class="row">
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="admin-metric">
                            <p class="admin-metric__label">Shows</p>
                            <p class="admin-metric__value"><?php echo (int) $countShows; ?></p>
                            <p class="admin-metric__hint">Total series in the library</p>
                            <div class="admin-metric__spark" aria-hidden="true"></div>
                            <div class="admin-metric__icon admin-metric__icon--green" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="7" width="20" height="15" rx="2"/><polyline points="17 2 12 7 7 2"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="admin-metric">
                            <p class="admin-metric__label">Episodes</p>
                            <p class="admin-metric__value"><?php echo (int) $countEpisodes; ?></p>
                            <p class="admin-metric__hint">Episodes published</p>
                            <div class="admin-metric__spark" aria-hidden="true"></div>
                            <div class="admin-metric__icon admin-metric__icon--blue" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="5 3 19 12 5 21 5 3"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="admin-metric">
                            <p class="admin-metric__label">Genres</p>
                            <p class="admin-metric__value"><?php echo (int) $countGenres; ?></p>
                            <p class="admin-metric__hint">Categories for browsing</p>
                            <div class="admin-metric__spark" aria-hidden="true"></div>
                            <div class="admin-metric__icon admin-metric__icon--violet" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3 mb-4">
                        <div class="admin-metric">
                            <p class="admin-metric__label">Admins</p>
                            <p class="admin-metric__value"><?php echo (int) $countAdmins; ?></p>
                            <p class="admin-metric__hint">Staff with panel access</p>
                            <div class="admin-metric__spark" aria-hidden="true"></div>
                            <div class="admin-metric__icon admin-metric__icon--amber" aria-hidden="true">
                                <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                            </div>
                        </div>
                    </div>
                </div>
   
  <?php require "layout/footer.php" ?>      
