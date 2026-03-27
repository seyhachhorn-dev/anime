<?php
require_once __DIR__ . "/init/init.php";

if (isset($_GET['id']) && !empty($_GET['id'])) {

    $showid = $_GET['id'];

    $showDetail = getShowDetailById((int)$showid);
    $showForYou = getForYouShows();
    $allComments = getAllCommentsByShowId($showid);

    if (isset($_POST['submit'])) {

        if (getCurrentUserId() === null) {
            header("Location: " . APPURL . "/auth/login.php");
            exit;
        }

        $show_id = $_POST['show_id'];
        $user_id = $_POST['id'];

        if (!empty($show_id) && !empty($user_id)) {
            insertFollow($show_id, $user_id);
        }
   header("Location: " . APPURL . "/anime-details.php?id=" . $showid);
         exit;
      
    }
}


?>
<?php require "includes/header.php"; ?>


<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?php echo APPURL; ?>"><i class="fa fa-home"></i> Home</a>
                    <a href="<?php echo APPURL ?>/anime-details.php?id=<?php echo $showDetail->id ?>">Details</a>
                    <span><?php echo $showDetail->title ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Anime Section Begin -->
<section class="anime-details spad">
    <div class="container">
        <div class="anime__details__content">
            <div class="row">
                <?php if ($showDetail) : ?>
                    <div class="col-lg-3">
                        <div class="anime__details__pic set-bg" data-setbg="img/<?php echo $showDetail->image ?>">
                            <!-- <div class="comment"><i class="fa fa-comments"></i> 11</div> -->
                            <div class="view"><i class="fa fa-eye"></i> <?php echo $showDetail->view_count ?></div>
                        </div>
                    </div>
                    <div class="col-lg-9">
                        <div class="anime__details__text">
                            <div class="anime__details__title">
                                <h3><?php echo $showDetail->title ?></h3>
                            </div>

                            <p><?php echo $showDetail->description ?></p>
                            <div class="anime__details__widget">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <li><span>Type:</span><?php echo $showDetail->type ?></li>
                                            <li><span>Studios:</span> <?php echo $showDetail->studio ?></li>
                                            <li><span>Date aired:</span><?php echo $showDetail->date_aired ?></li>
                                            <li><span>Status:</span> <?php echo $showDetail->status ?></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <ul>
                                            <li><span>Genre:</span><?php echo $showDetail->genre ?></li>

                                            <li><span>Duration:</span><?php echo $showDetail->duration ?>/ep</li>
                                            <li><span>Quality:</span><?php echo $showDetail->quality ?></li>
                                            <li><span>Views:</span><?php echo $showDetail->view_count ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="anime__details__btn">
                                <form method="POST" action="<?php echo APPURL ?>/anime-details.php?id=<?php echo $showid ?>" style="display: inline;">
                                    <input hidden type="text" name="show_id" value="<?php echo $showid ?>">
                                    <input hidden type="text" name="id" value="<?php echo getCurrentUserId() ?>">
                                    <?php if (checkFollowed($showid)): ?>
                                        <button  href="#" class="follow-btn" disabled><i class="fa fa-heart"></i> Followed</button>
                                    <?php else: ?>
                                        <button name="submit" type="submit" href="#" class="follow-btn"><i class="fa fa-heart-o"></i> Follow</button>
                                    <?php endif; ?>
                                </form>
                                
                                    <a href="anime-watching.html" class="watch-btn"><span>Watch Now</span> <i
                                            class="fa fa-angle-right"></i></a>

                            </div>
                        </div>
                    </div>
                <?php else : ?>
                    <div style="color: white; font-size: 1.25rem;">
                        <p>Update Soon!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 col-md-8">
                <div class="anime__details__review">
                    <div class="section-title">
                        <h5>Comments</h5>
                    </div>
                    <?php foreach ($allComments as $comments) : ?>

                        <div class="anime__review__item">
                            <div class="anime__review__item__pic">
                                <img src="img/review-1.jpg" alt="">
                            </div>
                            <div class="anime__review__item__text">
                                <h6><?php echo $comments->user_name ?> - <span><?php echo $comments->created_at ?></span></h6>
                                <p><?php echo $comments->comment ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>


                </div>
                <div class="anime__details__form">
                    <div class="section-title">
                        <h5>Your Comment</h5>
                    </div>
                    <form action="#">
                        <textarea placeholder="Your Comment"></textarea>
                        <button type="submit"><i class="fa fa-location-arrow"></i> Review</button>
                    </form>
                </div>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="anime__details__sidebar">
                    <div class="section-title">
                        <h5>you might like...</h5>
                    </div>
                    <?php foreach ($showForYou as $showYou) : ?>
                        <div class="product__sidebar__view__item set-bg" data-setbg="img/<?php echo $showYou->image ?>">
                            <div class="ep"><?php echo $showYou->num_avaliable ?> / <?php echo $showYou->num_total ?></div>
                            <div class="view"><i class="fa fa-eye"></i> <?php echo $showYou->view_count ?></div>
                            <h5><a href="<?php echo APPURL ?>/anime-details.php?id=<?php echo $showYou->id ?>"><?php echo $showYou->title ?></a></h5>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Anime Section End -->

<?php require "includes/footer.php"; ?>