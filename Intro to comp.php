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
   <title>Intro to components</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>
<section class="courses">

   <h1 class="heading">our courses</h1>

   <div class="box-container">

      <div class="box">
         <div class="thumb">
            <img src="images/introthumb.jpg" alt="">
            <span>10 minutes</span>
         </div>
         <h3 class="title">Component Tester</h3>
         <a href="Intro to arduino.html" class="inline-btn">view project</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/RC img.webp" alt="">
            <span>10 minutes</span>
         </div>
         <h3 class="title">Remote control car</h3>
         <a href="RC video.html" class="inline-btn">view project</a>
      </div>

      <div class="box">
         <div class="thumb">
            <img src="images/RA img.webp" alt="">
            <span>15 minutes</span>
         </div>
         <h3 class="title">Robotic arm</h3>
         <a href="RA video.html" class="inline-btn">view project</a>
      </div>
   </div>

</section>















<!-- custom js file link  -->
<script src="js/script.js"></script>

   
</body>
</html>