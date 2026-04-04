<?php
require_once __DIR__ . "/init/init.php";

$Allepisodes = [];
$allComments = [];
$showDetail = null;
$currentEpisode = null;
$getCurrentEpisodeInfo = null;
$showInfo = null;
$showid = null;
$epid = null;
$episode_number = '';

if (isset($_GET['id']) && !empty($_GET['ep'])) {

    $showid = $_GET['id'];
    $epid = $_GET['ep'];

    $Allepisodes = getEpisodesByShowId($showid);

    $getCurrentEpisodeInfo = getEpisodeInfoByShowIdAndEpId($showid, $epid);

    if ($getCurrentEpisodeInfo && getCurrentUserId() !== null) {
        insertViewForEachShow($showid, getCurrentUserId());
    }

    // displays all comments
    $allComments = getAllCommentsByShowId($showid);
    // grab show info
    $showInfo = getCustomInfoShowById($showid);
}

$videoUrl = '';
$posterUrl = '';
$videoType = 'video/mp4';
if ($getCurrentEpisodeInfo) {
    $videoFilename = trim((string) ($getCurrentEpisodeInfo->video ?? ''));
    $posterFilename = trim((string) ($getCurrentEpisodeInfo->thumbnail ?? ''));

    $videoCandidates = [
        __DIR__ . '/videos/' . $videoFilename => APPURL . '/videos/' . $videoFilename,
        __DIR__ . '/admin-panel/episodes-admins/videos/' . $videoFilename => APPURL . '/admin-panel/episodes-admins/videos/' . $videoFilename,
    ];

    foreach ($videoCandidates as $physical => $public) {
        if (!empty($videoFilename) && file_exists($physical)) {
            $videoUrl = $public;
            break;
        }
    }

    if (!empty($posterFilename)) {
        $posterPhysical = __DIR__ . '/img/' . $posterFilename;
        if (file_exists($posterPhysical)) {
            $posterUrl = APPURL . '/img/' . $posterFilename;
        }
    }

    if ($videoUrl !== '' && strtolower(pathinfo($videoUrl, PATHINFO_EXTENSION)) === 'm3u8') {
        $videoType = 'application/x-mpegURL';
    }
}


    //comment insert

    if (isset($_POST['insert_comment'])) {
        if (getCurrentUserId() === null) {

            header("Location: " . APPURL . "/auth/login.php");
            exit;
        }

        if (empty($_POST['comment'])) {
            echo "<script>alert('Your comment is empty')</script>";
        } else {

            $comment = $_POST['comment'];
            $show_id = $showid;
            $user_id = getCurrentUserId();
            $user_name = $_SESSION['username'];

            insertComment($comment, $show_id, $user_id, $user_name);

            header("Location: " . APPURL . "/anime-watching.php?id=" . $showid . "&ep=" . $epid);
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
                    <a href="<?php echo APPURL ?>"><i class="fa fa-home"></i> Home</a>
                    <a href="<?php echo APPURL ?>/categories.php?name=<?php echo $showInfo->genre ?>">Category</a>
                    <!-- <a href="#"></a> -->
                    <span><?php echo $showInfo->title ?></span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Anime Section Begin -->
<section class="anime-details spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php if (count($Allepisodes) > 0): ?>

                    <div class="anime__video__player">
                        <?php if (!empty($videoUrl)): ?>
                            <video id="player" playsinline controls preload="metadata" <?php echo $posterUrl ? 'poster="' . htmlspecialchars($posterUrl, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>>
                                <source src="<?php echo htmlspecialchars($videoUrl, ENT_QUOTES, 'UTF-8'); ?>" type="<?php echo htmlspecialchars($videoType, ENT_QUOTES, 'UTF-8'); ?>" />
                                <!-- Captions are optional -->
                                <track kind="captions" label="English captions" src="#" srclang="en" default />
                            </video>
                        <?php else: ?>
                            <p style="color:white; font-size: 1.25rem;">Episode file not found or video is unavailable.</p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <p style="color:white; font-size: 1.25rem;">Episode upload soon!</p>
                <?php endif; ?>



                <div class="anime__details__episodes">
                    <div class="section-title">
                        <h5>List Name</h5>
                    </div>
                    <?php foreach ($Allepisodes as $episodes): ?>

                        <a href="<?php echo APPURL ?>/anime-watching.php?id=<?php echo $episodes->show_id ?>&ep=<?php echo $episodes->episode_number ?>">Ep <?php echo $episodes->episode_number ?></a>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="anime__details__review">
                    <div class="section-title">
                        <h5>Commented</h5>
                    </div>
                    <?php foreach ($allComments as $showComment) : ?>
                        <div class="anime__review__item">
                            <div class="anime__review__item__pic">
                                <img src="img/review-1.jpg" alt="">
                            </div>
                            <div class="anime__review__item__text">
                                <h6><?php echo $showComment->user_name ?> - <span><?php echo $showComment->created_at ?></span></h6>
                                <p><?php echo $showComment->comment ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="anime__details__form">
                    <div class="section-title">
                        <h5>Your Comment</h5>
                    </div>
                    <?php if ($showid !== null && $epid !== null): ?>
                        <form method="post" action="<?php echo APPURL ?>/anime-watching.php?id=<?php echo $showid ?>&ep=<?php echo $epid ?>">
                            <textarea name="comment" placeholder="Your Comment"></textarea>
                            <button type="submit" name="insert_comment">
                                <i class="fa fa-location-arrow"></i> Send
                            </button>
                        </form>
                    <?php else: ?>
                        <p style="color:white;">Episode not found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Anime Section End -->
<?php require "includes/footer.php"; ?>