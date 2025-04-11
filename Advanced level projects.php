<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Intro to Arduino</title>

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="courses">
   <h1 class="heading">Advanced level projects</h1>

   <div class="box-container">
      <?php
      // Fetch all videos from the 'content' table, ordered by ID
      $videos_query = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = 4 ORDER BY id ASC");
      $videos_query->execute();

      // Check if there are any videos in the playlist
      if ($videos_query->rowCount() > 0) {
         while ($video = $videos_query->fetch(PDO::FETCH_ASSOC)) {
      ?>
            <div class="box">
               <div class="thumb">
                  <img src="<?= htmlspecialchars($video['thumb']); ?>" alt="Thumbnail">
                  <span><?= htmlspecialchars($video['duration']); ?> minutes</span>
               </div>
               <h3 class="title"><?= htmlspecialchars($video['title']); ?></h3>
               <!-- Dynamically create the link using the video id -->
               <a href="watch-video.php?get_id=<?= $video['id']; ?>" class="inline-btn">view video</a>
            </div>
      <?php
         }
      } else {
         echo "<p>No videos found in this playlist.</p>";
      }
      ?>
   </div>
</section>

<section class="courses">

   <h1 class="heading">Assessments for Intermediate</h1>

   <div class="box-container">
   <div class="box">
        <div class="thumb">
           <img src="images/AQ.jpg" alt="">
        </div>
        <h3 class="title">Advanced Quiz</h3>
        <a href="advanced_quiz.php" class="inline-btn">Take Quiz</a>
     </div>
   </div>
</section>


<!-- custom js file link -->
<script src="js/script.js"></script>

</body>
</html>
