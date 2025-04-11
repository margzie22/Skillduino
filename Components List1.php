<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Fetch all components from the database
$query = $conn->prepare("SELECT * FROM components");
$query->execute();
$components = $query->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Components List</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="courses">
   <h1 class="heading">Component Tester Module Introduction</h1>

   <div class="box-container">
   <?php
      // Fetch the video with id 23 from the 'content' table
      $videos_query = $conn->prepare("SELECT * FROM `content` WHERE id = 29");
      $videos_query->execute();

      // Check if the video exists
      if ($videos_query->rowCount() > 0) {
         $video = $videos_query->fetch(PDO::FETCH_ASSOC); // Fetch the video row
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
      } else {
         echo "<p>No video found with ID 23.</p>";
      }
      ?>
   </div>
</section>

<section class="courses">
   <h1 class="heading">Components List</h1>

   <div class="box-container">
      <?php foreach ($components as $component): ?>
         <div class="box">
            <div class="thumb">
               <!-- Link with the component id as a parameter in the URL -->
               <a href="component-info.php?id=<?= htmlspecialchars($component['id']); ?>">
                  <img src="images/<?= htmlspecialchars($component['image']); ?>" alt="<?= htmlspecialchars($component['name']); ?>">
               </a>
            </div>
            <h3 class="title"><?= htmlspecialchars($component['name']); ?></h3>
            <!-- Same link for "More information" with the component id -->
            <a href="component-info.php?id=<?= htmlspecialchars($component['id']); ?>" class="inline-btn">More information</a>
         </div>
      <?php endforeach; ?>
   </div>
</section>


<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
