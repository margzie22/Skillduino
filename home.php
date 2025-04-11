<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

$select_comments = $conn->prepare("SELECT * FROM `comments` WHERE user_id = ?");
$select_comments->execute([$user_id]);
$total_comments = $select_comments->rowCount();

$select_bookmark = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ?");
$select_bookmark->execute([$user_id]);
$total_bookmarked = $select_bookmark->rowCount();



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>home</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- quick select section starts  -->

<div class="banner">
   <img src="images/skillduino banner.jpg" alt="Banner">
</div>

<div class="banner">
   <img src="images/aboutus.jpg" alt="Banner">
</div>

<div class="banner">
   <img src="images/EYC1.jpg" alt="Banner">
</div>


<section class="home-grid">
<h1 class="heading">quick options</h1>

   <div class="box-container">

      <?php
         if($user_id != ''){
      ?>
      <div class="box">
         <h3 class="title">Saved videos and comments</h3>
         <p>saved projects : <span><?= $total_bookmarked; ?></span></p>
         <a href="bookmark.php" class="inline-btn">Saved videos</a>
         <p>total comments : <span><?= $total_comments; ?></span></p>
         <a href="comments.php" class="inline-btn">view comments</a>
      </div>
      <?php
         }else{ 
      ?>
      <div class="box" style="text-align: center;">
         <h3 class="title">please login or register</h3>
          <div class="flex-btn" style="padding-top: .5rem;">
            <a href="login.php" class="option-btn">login</a>
            <a href="register.php" class="option-btn">register</a>
         </div>
      </div>
      <?php
      }
      ?>

      <div class="box">
         <h3 class="title">Competency Test</h3>
         <p class="tutor">Unsure where to start? Answer this short form to get your starting point!</p>
         <a href="Skillduino Competency test.xlsx" download="Competency Test.xlsx" class="inline-btn">Download Competency Test</a>
      </div>

      <div class="box">
         <h3 class="title">Have any questions?</h3>
         <p class="tutor">Email us your questions and get your responses within 3-4 working days.</p>
         <a href="contact.php" class="inline-btn">Contact Us</a>
      </div>

   </div>

</section>

<?php
// Fetch random videos from the database
$query = $conn->prepare("SELECT * FROM content ORDER BY RAND() LIMIT 3");
$query->execute();
$random_videos = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="playlist-videos">
   <h1 class="heading">Recommended Videos</h1>

   <div class="box-container">
      <?php foreach ($random_videos as $video): ?>
         <a href="watch-video.php?get_id=<?= htmlspecialchars($video['id']); ?>" class="box">
            <i class="fas fa-play"></i>
            <img src="<?= htmlspecialchars($video['thumb']); ?>" alt="<?= htmlspecialchars($video['title']); ?>">
            <h3><?= htmlspecialchars($video['title']); ?></h3>
         </a>
      <?php endforeach; ?>
   </div>
</section>


<!-- courses section ends -->












<!-- footer section starts  -->

<!-- footer section ends -->

<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>