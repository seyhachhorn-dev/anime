<?php
require_once __DIR__ . "/init/init.php";

// DO NOT require login here.
// Guest users should still be able to watch anime and read comments.

$Allepisodes = [];
$allComments = [];
$showDetail = null;
$currentEpisode = null;
$getCurrentEpisodeInfo = null;
$showInfo = null;
$showid = null;
$epid = null;
$episode_number = '';

if (isset($_GET['id']) && isset($_GET['ep']) && !empty($_GET['id']) && !empty($_GET['ep'])) {

    $showid = (int) $_GET['id'];
    $epid = (int) $_GET['ep'];

    $Allepisodes = getEpisodesByShowId($showid);
    $getCurrentEpisodeInfo = getEpisodeInfoByShowIdAndEpId($showid, $epid);

    // Only record view if user is logged in
    if ($getCurrentEpisodeInfo && getCurrentUserId() !== null) {
        insertViewForEachShow($showid, getCurrentUserId());
    }

    // Display all comments
    $allComments = getAllCommentsByShowId($showid);

    // Grab show info
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
?>

<?php require "includes/header.php"; ?>

<!-- Breadcrumb Begin -->
<div class="breadcrumb-option">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb__links">
                    <a href="<?php echo APPURL; ?>"><i class="fa fa-home"></i> Home</a>

                    <?php if ($showInfo): ?>
                        <a href="<?php echo APPURL; ?>/categories.php?name=<?php echo urlencode($showInfo->genre); ?>">Category</a>
                        <span><?php echo htmlspecialchars($showInfo->title, ENT_QUOTES, 'UTF-8'); ?></span>
                    <?php else: ?>
                        <span>Anime Watching</span>
                    <?php endif; ?>
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
                            <video
                                id="player"
                                playsinline
                                controls
                                preload="metadata"
                                <?php echo $posterUrl ? 'poster="' . htmlspecialchars($posterUrl, ENT_QUOTES, 'UTF-8') . '"' : ''; ?>
                            >
                                <source
                                    src="<?php echo htmlspecialchars($videoUrl, ENT_QUOTES, 'UTF-8'); ?>"
                                    type="<?php echo htmlspecialchars($videoType, ENT_QUOTES, 'UTF-8'); ?>"
                                />
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
                        <a href="<?php echo APPURL; ?>/anime-watching.php?id=<?php echo (int) $episodes->show_id; ?>&ep=<?php echo (int) $episodes->episode_number; ?>">
                            Ep <?php echo (int) $episodes->episode_number; ?>
                        </a>
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

                    <div id="comments-container-watching">
                        <?php foreach ($allComments as $showComment): ?>
                            <div class="anime__review__item">
                                <div class="anime__review__item__pic">
                                    <img src="<?php echo APPURL; ?>/img/review-1.jpg" alt="">
                                </div>
                                <div class="anime__review__item__text">
                                    <h6>
                                        <?php echo htmlspecialchars($showComment->user_name, ENT_QUOTES, 'UTF-8'); ?>
                                        -
                                        <span><?php echo htmlspecialchars($showComment->created_at, ENT_QUOTES, 'UTF-8'); ?></span>
                                    </h6>
                                    <p><?php echo htmlspecialchars($showComment->comment, ENT_QUOTES, 'UTF-8'); ?></p>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="anime__details__form">
                    <div class="section-title">
                        <h5>Your Comment</h5>
                    </div>

                    <?php if ($showid !== null && $epid !== null): ?>
                        <form id="comment-form-watching" method="post">
                            <textarea
                                name="comment"
                                id="comment-input-watching"
                                placeholder="Your Comment"
                            ></textarea>

                            <button type="submit" id="submit-comment-watching">
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

<script>
document.addEventListener('DOMContentLoaded', function () {
    var form = document.getElementById('comment-form-watching');
    var submitBtn = document.getElementById('submit-comment-watching');
    var commentInput = document.getElementById('comment-input-watching');
    var commentsContainer = document.getElementById('comments-container-watching');

    if (!form) return;

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        var commentText = commentInput.value.trim();
        var showId = <?php echo $showid !== null ? (int) $showid : 'null'; ?>;

        if (commentText === '') {
            swal({
                title: "Error!",
                text: "Comment cannot be empty",
                icon: "error",
                button: "OK"
            });
            return;
        }

        if (showId === null) {
            swal({
                title: "Error!",
                text: "Show ID not found",
                icon: "error",
                button: "OK"
            });
            return;
        }

        submitBtn.disabled = true;

        fetch('<?php echo APPURL; ?>/add-comment-ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'comment=' + encodeURIComponent(commentText) + '&show_id=' + encodeURIComponent(showId)
        })
        .then(async function (response) {
            var data;

            try {
                data = await response.json();
            } catch (e) {
                throw new Error('Invalid JSON response');
            }

            if (response.status === 401) {
                window.location.href = data.redirect || '<?php echo APPURL; ?>/auth/login.php';
                return null;
            }

            if (!response.ok) {
                throw new Error(data.message || 'Failed to add comment');
            }

            return data;
        })
        .then(function (data) {
            if (!data) return;

            if (data.success) {
                var newComment = `
                    <div class="anime__review__item">
                        <div class="anime__review__item__pic">
                            <img src="<?php echo APPURL; ?>/img/review-1.jpg" alt="">
                        </div>
                        <div class="anime__review__item__text">
                            <h6>${data.comment.user_name} - <span>${data.comment.created_at}</span></h6>
                            <p>${data.comment.comment}</p>
                        </div>
                    </div>
                `;

                commentsContainer.insertAdjacentHTML('afterbegin', newComment);
                commentInput.value = '';

                swal({
                    title: "Success!",
                    text: data.message || "Comment added successfully",
                    icon: "success",
                    button: "OK"
                });
            } else {
                swal({
                    title: "Error!",
                    text: data.message || "Failed to add comment",
                    icon: "error",
                    button: "OK"
                });
            }
        })
        .catch(function (error) {
            swal({
                title: "Error!",
                text: error.message || "Failed to add comment",
                icon: "error",
                button: "OK"
            });
        })
        .finally(function () {
            submitBtn.disabled = false;
        });
    });
});
</script>

<?php require "includes/footer.php"; ?>