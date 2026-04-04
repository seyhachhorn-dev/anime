<?php require "includes/header.php"; ?>

<?php
$allShows = getHeroShows(3);
$allTrendingShows = getTrendingShows();
$alladventureShows = getShowsByGenre('Adventure');
$allRecentlyShows = getRecentlyAddedShows();
$allActionShows = getShowsByGenre('Action');
$allForYouShows = getForYouShows(3);

// var_dump($allForYouShows)



?>

<!-- Hero Section Begin -->
<section class="hero">
    <div class="container">
        <div class="hero__slider owl-carousel">
            <?php foreach ($allShows as $show) : ?>
                <div class="hero__items set-bg" data-setbg="img/<?php echo $show->image;  ?>">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="hero__text">
                                <div class="label"><?php echo $show->genre; ?></div>
                                <h2><?php echo $show->title; ?></h2>
                                <p><?php echo $show->description; ?></p>
                                <a href="anime-watching.php?id<?php echo $show->id; ?>"><span>Watch Now</span> <i class="fa fa-angle-right"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;  ?>

        </div>
    </div>
</section>
<!-- Hero Section End -->

<!-- Product Section Begin -->
<section class="product spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="trending__product">
                    <div class="row">
                        <div class="col-lg-8 col-md-8 col-sm-8">
                            <div class="section-title">
                                <h4>Trending Now</h4>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="btn__all">
                                <a href="#" class="primary-btn">View All <span class="arrow_right"></span></a>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <?php foreach ($allTrendingShows as $trShow) : ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg" data-setbg="img/<?php echo $trShow->image ?>">
                                        <div class="ep"><?php echo $trShow->num_avaliable ?> / <?php echo $trShow->num_total ?></div>
                                        <!-- <div class="comment"><i class="fa fa-comments"></i> 11</div> -->
                                        <div class="view"><i class="fa fa-eye"></i> <?php echo $trShow->view_count ?></div>
                                    </div>
                                    <div class="product__item__text">
                                        <ul>
                                            <li><?php echo $trShow->genre ?></li>
                                            <li><?php echo $trShow->type ?></li>
                                        </ul>
                                        <h5><a href="<?php echo APPURL; ?>/anime-details.php?id=<?php echo $trShow->id?>"><?php echo $trShow->title ?></a></h5>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                    </div>
                    <div class="popular__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Adventure Shows</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="btn__all">
                                    <a href="<?php echo APPURL; ?>/categories.php?name=Adventure" class="primary-btn">View All <span class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php foreach($alladventureShows as $adventureShow) : ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg" data-setbg="img/<?php echo $adventureShow->image ?>">
                                        <div class="ep"><?php echo $adventureShow->num_avaliable ?> / <?php echo $adventureShow->num_total ?></div>
                                        <!-- <div class="comment"><i class="fa fa-comments"></i> 11</div> -->
                                        <div class="view"><i class="fa fa-eye"></i> <?php echo $adventureShow->view_count ?></div>
                                    </div>
                                    <div class="product__item__text">
                                        <ul>
                                            <li><?php echo $adventureShow->genre ?></li>
                                            <li><?php echo $adventureShow->type ?></li>
                                        </ul>
                                        <h5><a href="<?php echo APPURL; ?>/anime-details.php?id=<?php echo $adventureShow->id?>"><?php echo $adventureShow->title ?></a></h5>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>


                        </div>
                    </div>
                    <div class="recent__product">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-8">
                                <div class="section-title">
                                    <h4>Recently Added Shows</h4>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4">
                                <div class="btn__all">
                                    <a href="#" class="primary-btn">View All <span class="arrow_right"></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <?php foreach($allRecentlyShows as $recentlyShows) : ?>
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="product__item">
                                    <div class="product__item__pic set-bg" data-setbg="img/<?php echo $recentlyShows->image ?>">
                                        <div class="ep"><?php echo $recentlyShows->num_avaliable ?> / <?php echo $recentlyShows->num_total ?></div>
                                        <!-- <div class="comment"><i class="fa fa-comments"></i> 11</div> -->
                                        <div class="view"><i class="fa fa-eye"></i> <?php echo $recentlyShows->view_count ?></div>
                                    </div>
                                    <div class="product__item__text">
                                        <ul>
                                            <li><?php echo $recentlyShows->genre ?></li>
                                            <li><?php echo $recentlyShows->type ?></li>
                                        </ul>
                                        <h5><a href="<?php echo APPURL; ?>/anime-details.php?id=<?php echo $recentlyShows->id?>"><?php echo $recentlyShows->title ?></a></h5>
                                    </div>
                                </div>
                            </div>
                          <?php endforeach; ?>
                        </div>

                        <div class="live__product">
                            <div class="row">
                                <div class="col-lg-8 col-md-8 col-sm-8">
                                    <div class="section-title">
                                        <h4>Live Action</h4>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4">
                                    <div class="btn__all">
                                        <a href="<?php echo APPURL; ?>/categories.php?name=Action" class="primary-btn">View All <span class="arrow_right"></span></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <?php foreach($allActionShows as $actionShows) : ?>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg" data-setbg="img/<?php echo $actionShows->image ?>">
                                            <div class="ep"><?php echo $actionShows->num_avaliable ?> / <?php echo $actionShows->num_total ?></div>
                                            <!-- <div class="comment"><i class="fa fa-comments"></i> 11</div> -->
                                            <div class="view"><i class="fa fa-eye"></i> <?php echo $actionShows->view_count?></div>
                                        </div>
                                        <div class="product__item__text">
                                            <ul>
                                                <li><?php echo $actionShows->genre ?></li>
                                                <li><?php echo $actionShows->type ?></li>
                                            </ul>
                                            <h5><a href="<?php echo APPURL; ?>/anime-details.php?id=<?php echo $actionShows->id?>"><?php echo $actionShows->title ?></a></h5>
                                        </div>
                                    </div>
                                </div>
                                <?php endforeach; ?>

                            </div>
                        </div>
                    </div>

                </div> <!-- /.trending__product -->
            </div> <!-- /.col-lg-8 -->

            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="product__sidebar">
                    <div class="product__sidebar__view">
                    </div>

                    <div class="product__sidebar__comment">
                        <div class="section-title">
                            <h5>For You</h5>
                        </div>

                        <?php foreach($allForYouShows as $forYouShows): ?>
                        <div class="product__sidebar__comment__item">
                            <div class="product__sidebar__comment__item__pic">
                                <img style="width: 120px; height: 150px;" src="img/<?php echo $forYouShows->image ?>" alt="">
                            </div>
                            <div class="product__sidebar__comment__item__text">
                                <ul>
                                    <li style="width: 100px; height: auto; text-align: center;"><?php echo $forYouShows->genre ?></li>
                                    <li><?php echo $forYouShows->type ?></li>
                                </ul>
                                <h5><a href="<?php echo APPURL; ?>/anime-details.php?id=<?php echo $forYouShows->id ?>"><?php echo $forYouShows->title ?></a></h5>
                                <span><i class="fa fa-eye"></i> <?php echo $forYouShows->view_count ?> Viewes</span>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            </div>
        </div>
</section>
<!-- Product Section End -->

<!-- Footer Section Begin -->
<?php require "includes/footer.php"; ?>