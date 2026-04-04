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
                                
                                    <a href="<?php echo APPURL ?>/anime-watching.php?id=<?php echo $showid ?>&ep=1" class="watch-btn"><span>Watch Now</span> <i
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
                    <div id="comments-container">
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

                </div>
                <div class="anime__details__form">
                    <div class="section-title">
                        <h5>Your Comment</h5>
                    </div>
                    <form id="comment-form" method="POST">
                        <textarea name="comment" id="comment-input" placeholder="Your Comment"></textarea>
                        <button type="submit" id="submit-comment"><i class="fa fa-location-arrow"></i>Send</button>
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


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle comment form submission
    var form = document.getElementById('comment-form');
    var submitBtn = document.getElementById('submit-comment');
    var commentInput = document.getElementById('comment-input');
    
    if (!form) return; // Exit if form not found
    
    form.addEventListener('submit', function(e) {
        e.preventDefault(); // Prevent page reload
        
        var commentText = commentInput.value.trim();
        var showId = <?php echo $showid; ?>;
        
        // Validate comment
        if (commentText === '') {
            swal({
                title: "Error!",
                text: "Comment cannot be empty",
                icon: "error",
                button: "OK",
            });
            return;
        }
        
        // Disable submit button
        submitBtn.disabled = true;
        
        // Send request using Fetch API (no jQuery needed)
        fetch('<?php echo APPURL; ?>/add-comment-ajax.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'comment=' + encodeURIComponent(commentText) + '&show_id=' + showId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add new comment to the comments container
                var newComment = `
                    <div class="anime__review__item">
                        <div class="anime__review__item__pic">
                            <img src="img/review-1.jpg" alt="">
                        </div>
                        <div class="anime__review__item__text">
                            <h6>` + data.comment.user_name + ` - <span>` + data.comment.created_at + `</span></h6>
                            <p>` + data.comment.comment + `</p>
                        </div>
                    </div>
                `;
                
                document.getElementById('comments-container').insertAdjacentHTML('afterbegin', newComment);
                
                // Clear textarea
                commentInput.value = '';
                
                // Show success message
                swal({
                    title: "Success!",
                    text: "Comment added successfully",
                    icon: "success",
                    button: "OK",
                });
            } else {
                swal({
                    title: "Error!",
                    text: data.message,
                    icon: "error",
                    button: "OK",
                });
            }
        })
        .catch(error => {
            swal({
                title: "Error!",
                text: "Failed to add comment",
                icon: "error",
                button: "OK",
            });
        })
        .finally(() => {
            // Re-enable submit button
            submitBtn.disabled = false;
        });
    });
});
</script>

<?php require "includes/footer.php"; ?>