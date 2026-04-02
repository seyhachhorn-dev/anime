
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
      <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Shows</h5>
              <!-- <h6 class="card-subtitle mb-2 text-muted">Bootstrap 4.0.0 Snippet by pradeep330</h6> -->
              <p class="card-text">number of shows: <?php echo $countShows?></p>
             
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Episodes</h5>
              
              <p class="card-text">number of episodes: <?php echo $countEpisodes?></p>
              
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Genres</h5>
              
              <p class="card-text">number of genres: <?php echo $countGenres?></p>
              
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Admins</h5>
              
              <p class="card-text">number of admins: <?php echo $countAdmins?></p>
              
            </div>
          </div>
        </div>
      </div>
   
  <?php require "layout/footer.php" ?>      
