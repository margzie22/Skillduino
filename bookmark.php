<?php
include 'components/connect.php';

// Check if the user is logged in via cookie
if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    // Redirect to home page if not logged in
    header('location: home.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Bookmarked Videos</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="courses">
   <h1 class="heading">Bookmarked Videos</h1>

   <div class="box-container">

      <?php
         // Fetch all bookmarked videos for the user
         $select_bookmarks = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
         $select_bookmarks->execute([$user_id]);

         if ($select_bookmarks->rowCount() > 0) {
            // Loop through each bookmark
            while ($bookmark = $select_bookmarks->fetch(PDO::FETCH_ASSOC)) {
                // Fetch the video details from the 'content' table using playlist_id
                $select_video = $conn->prepare("SELECT * FROM `content` WHERE id = ?");
                $select_video->execute([$bookmark['playlist_id']]);

                if ($select_video->rowCount() > 0) {
                    $video = $select_video->fetch(PDO::FETCH_ASSOC);
      ?>
                    <div class="box">
                        <div class="thumb">
                            <img src="<?= $video['thumb']; ?>" alt="Video Thumbnail">
                            <span><?= htmlspecialchars($video['duration']); ?> minutes</span>
                        </div>
                        <h3 class="title"><?= htmlspecialchars($video['title']); ?></h3>
                        <a href="watch-video.php?get_id=<?= $video['id']; ?>" class="inline-btn">View Video</a>
                    </div>
      <?php
                }
            }
         } else {
            echo "<p class='empty'>You have no bookmarked videos.</p>";
         }
      ?>

   </div>
</section>

<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
