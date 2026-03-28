<?php require "includes/header.php"; ?>

<?php
 $allSearchShows=[];
 $keyword='';

if (isset($_GET['keyword']) && !empty(trim($_GET['keyword']))) {
    $keyword = trim($_GET['keyword']);
    $allSearchShows = getShowsBySearch($keyword);
}


$allForYouShows = getForYouShows();





?>

<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?php echo APPURL; ?>"><i class="fa fa-home"></i> Home</a>
                    <a href="<?php echo APPURL; ?>/searching.php">Search</a>
                    <span>Anime</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Product Section Begin -->
<section class="product-page spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <div class="product__page__content">
                    <div class="product__page__title">
                        <div class="row">
                            <div class="col-lg-8 col-md-8 col-sm-6">
                                <div class="section-title">
                                    <h4>Searching</h4>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="row">
                        <?php if (count($allSearchShows) > 0): ?>
                            <?php foreach ($allSearchShows as $shows) : ?>
                                <div class="col-lg-4 col-md-6 col-sm-6">
                                    <div class="product__item">
                                        <div class="product__item__pic set-bg" data-setbg="<?php echo APPURL ?>/img/<?php echo $shows->image ?> ">
                                            <div class="ep"><?php echo $shows->num_avaliable ?> / <?php echo $shows->num_total ?> </div>
                                        </div>
                                        <div class="product__item__text">
                                            <ul>
                                                <li><?php echo $shows->genre ?> </li>
                                                <li><?php echo $shows->type ?> </li>
                                            </ul>
                                            <h5><a href="<?php echo APPURL; ?>/anime-details.php?id=<?php echo $shows->show_id ?>"><?php echo $shows->title ?> </a></h5>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <p style="font-size: 1.25rem; color: white;">No shows in this genre just yet</p>
                        <?php endif; ?>

                    </div>
                </div>

            </div>
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="product__sidebar">
                    <div class="product__sidebar__view">
                    </div>
                    <!-- </div>
                </div>         -->
                </div>
                <div class="product__sidebar__comment">
                    <div class="section-title">
                        <h5>FOR YOU</h5>
                    </div>
                    <?php foreach ($allForYouShows as $forYouShows) : ?>
                        <div class="product__sidebar__comment__item">
                            <div class="product__sidebar__comment__item__pic">
                                <img style="width: 120px; height: 150px;" src="img/<?php echo $forYouShows->image ?>" alt="">
                            </div>
                            <div class="product__sidebar__comment__item__text">
                                <ul>
                                    <li><?php echo $forYouShows->genre ?></li>
                                    <li><?php echo $forYouShows->type ?></li>
                                </ul>
                                <h5><a href="<?php echo APPURL ?>/anime-details.php?id=<?php echo $forYouShows->id ?>"><?php echo $forYouShows->title ?></a></h5>
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