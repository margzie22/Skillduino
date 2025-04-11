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
   <title>Courses</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- courses section starts  -->

<section class="courses">

   <h1 class="heading">our courses</h1>

   <div class="box-container">

         <!-- Component tester project - only visible to logged-in users -->
      <?php if($user_id): ?>
         <div class="box">
            <div class="thumb">
               <img src="images/Componenttesterthumb.jpg" alt="">
            </div>
            <h3 class="title">Component Tester Course</h3>
            <a href="Component tester project.php" class="inline-btn">view course</a>
         </div>
      <?php else: ?>
         <div class="box">
            <div class="thumb">
               <img src="images/Componenttesterthumb.jpg" alt="">
            </div>
            <h3 class="title">Component Tester Course</h3>
            <a href="login.php" class="inline-btn" onclick="alert('Please log in to continue.')">Login to View</a>
         </div>
      <?php endif; ?>

   <div class="box">
         <div class="thumb">
            <img src="images/introthumb.jpg" alt="">
         </div>
         <h3 class="title">Introduction to Arduino</h3>
         <a href="Intro to arduino.php" class="inline-btn">view playlist</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/Easythumb.jpg" alt="">
         </div>
         <h3 class="title">Beginner level projects</h3>
         <a href="Beginner level projects.php" class="inline-btn">view playlist</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/intermediatethumb.jpg" alt="">
         </div>
         <h3 class="title">Intermediate level projects</h3>
         <a href="Intermediate level projects.php" class="inline-btn">view playlist</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/advancedthumb.jpg" alt="">
         </div>
         <h3 class="title">Advanced level projects</h3>
         <a href="Advanced level projects.php" class="inline-btn">view playlist</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/Techskillthumb.jpg" alt="">
         </div>
         <h3 class="title">Technical Skills</h3>
         <a href="Technical skills.php" class="inline-btn">view playlist</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/ComponentListthumb.jpg" alt="">
         </div>
         <h3 class="title">Components List</h3>
         <a href="Components List1.php" class="inline-btn">view List</a>
      </div>

   </div>

</section>

<!-- courses section ends -->












<!-- custom js file link  -->
<script src="js/script.js"></script>
   
</body>
</html>