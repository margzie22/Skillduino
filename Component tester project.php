<?php

include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

if (isset($_POST['mark_complete'])) {
   if (isset($_COOKIE['user_id'])) {
       $user_id = $_COOKIE['user_id'];
       $content_id = $_POST['content_id']; // This would be the ID of the content being marked complete
       
       // Update the progress to mark as completed
       $update_progress = $conn->prepare("INSERT INTO `user_progress` (user_id, content_id, is_completed) VALUES (:user_id, :content_id, 1)
                                          ON DUPLICATE KEY UPDATE is_completed = 1");
       $update_progress->bindParam(':user_id', $user_id);
       $update_progress->bindParam(':content_id', $content_id);
       $update_progress->execute();
       
       echo "Content marked as completed!";
   }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Component tester course</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="courses">
   <h1 class="heading">Component Tester Module Introduction</h1>

   <div class="box-container">
      <?php
      // Fetch all videos from the 'content' table, ordered by ID
      $videos_query = $conn->prepare("SELECT * FROM `content` WHERE playlist_id = 7 ORDER BY id ASC");
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
               <a href="watch-video1.php?get_id=<?= $video['id']; ?>" class="inline-btn">view video</a>
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
   <h1 class="heading">Component Tester Module 1</h1>

   <div class="box-container">

      <!-- Reading Section -->
      <div class="box">
         <div class="thumb">
            <img src="images/ohmmeterthumb.jpg" alt="">
         </div>
         <h3 class="title">Reading Document 1: Introduction and Ohmmeter Background</h3>
         <a href="Reading 1.php" class="inline-btn">Read now</a>
      </div>

      <?php
      // Fetch the reading content or video (ID 23 as example, replace if different content ID)
      $content_query = $conn->prepare("SELECT * FROM `content` WHERE id = 23");
      $content_query->execute();

      // Check if the content exists
      if ($content_query->rowCount() > 0) {
         $content = $content_query->fetch(PDO::FETCH_ASSOC); // Fetch content row
         
         // Check if the user is logged in
         if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];

            // Query to check if the user has completed this content (ID 23)
            $progress_query = $conn->prepare("SELECT * FROM `user_progress` WHERE user_id = :user_id AND content_id = 1");
            $progress_query->bindParam(':user_id', $user_id);
            $progress_query->execute();
            $progress = $progress_query->fetch(PDO::FETCH_ASSOC);
            
            // If the user has completed the content
            if ($progress && $progress['is_completed'] == 1) {
               // Unlock next content (i.e., the quiz or the next video/document)
               $next_content_query = $conn->prepare("SELECT * FROM `course_sequence` WHERE `order` = 2"); // Assuming 2 is the next content order
               $next_content_query->execute();
               if ($next_content_query->rowCount() > 0) {
                  $next_content = $next_content_query->fetch(PDO::FETCH_ASSOC);
                  
                  // Check if next content is a video or quiz, and update the content ID
                  if ($next_content['module_type'] == 'video') {
                     echo '
                     <div class="box">
                        <div class="thumb">
                           <img src="' . htmlspecialchars($content['thumb']) . '" alt="Thumbnail">
                           <span>' . htmlspecialchars($content['duration']) . ' minutes</span>
                        </div>
                        <h3 class="title">' . htmlspecialchars($next_content['module_title']) . '</h3>
                        <a href="watch-video2.php?get_id=23" class="inline-btn">View Video</a>
                     </div>';
                  } elseif ($next_content['module_type'] == 'quiz') {
                     echo '
                     <div class="box">
                        <div class="thumb">
                           <img src="images/easylevel.jpg" alt="">
                        </div>
                        <h3 class="title">' . htmlspecialchars($next_content['module_title']) . '</h3>
                        <a href="Easy CT assessment.php" class="inline-btn">Take Quiz</a>
                     </div>';
                  }
               }
            } else {
               // If the user has not completed the content, show the locked message
               echo '
               <div class="box">
                  <div class="thumb">
                     <img src="' . htmlspecialchars($content['thumb']) . '" alt="Thumbnail">
                     <span>' . htmlspecialchars($content['duration']) . ' minutes</span>
                  </div>
                  <h3 class="title">' . htmlspecialchars($content['title']) . '</h3>
                  <p class="locked">Complete the previous section to unlock this content.</p>
               </div>';
            }
         } else {
            // If the user is not logged in, show the locked message
            echo '
            <div class="box">
               <div class="thumb">
                  <img src="' . htmlspecialchars($content['thumb']) . '" alt="Thumbnail">
                  <span>' . htmlspecialchars($content['duration']) . ' minutes</span>
               </div>
               <h3 class="title">' . htmlspecialchars($content['title']) . '</h3>
               <p class="locked">You need to log in to track your progress and unlock content.</p>
            </div>';
         }
      } else {
         echo "<p>No content found.</p>";
      }
      ?>

      <!-- Quiz Section (Only visible if the previous content is completed) -->
      <div class="box">
         <div class="thumb">
            <img src="images/thumbnail.jpg" alt="">
         </div>
         <h3 class="title">Component tester Module 1 Quiz with Wizard Toadie</h3>

         <?php
         $quiz_query = $conn->prepare("SELECT * FROM `content` WHERE id = 3");
         $quiz_query->execute();

         if ($quiz_query->rowCount() > 0) {
            $quiz = $quiz_query->fetch(PDO::FETCH_ASSOC);

            // Check if the user has completed the previous content (ID 23)
            $quiz_progress_query = $conn->prepare("SELECT * FROM `user_progress` WHERE user_id = :user_id AND content_id = 2");
            $quiz_progress_query->bindParam(':user_id', $user_id);
            $quiz_progress_query->execute();
            $quiz_progress = $quiz_progress_query->fetch(PDO::FETCH_ASSOC);

            if ($quiz_progress && $quiz_progress['is_completed'] == 1) {
               // User has completed the previous content, show the quiz link
               echo '<a href="Module 1 Quiz.php" class="inline-btn">Take Quiz</a>';
            } else {
               // User has not completed the previous content, show a message
               echo '<p>You need to complete the previous section to access this quiz.</p>';
            }
         } else {
            echo "<p>No quiz found.</p>";
         }
         ?>
      </div>
   </div>
</section>


<!-- Reading Document Section -->
<section class="courses">
   <h1 class="heading">Component Tester Module 2</h1>

   <div class="box-container">
   <div class="box">
         <div class="thumb">
            <img src="images/voltmetersim.jpg" alt="">
         </div>
         <h3 class="title">Reading Document 2: Introduction to Voltmeter simulation</h3>

         <?php
         $quiz_query = $conn->prepare("SELECT * FROM `content` WHERE id = 4");
         $quiz_query->execute();

         if ($quiz_query->rowCount() > 0) {
            $quiz = $quiz_query->fetch(PDO::FETCH_ASSOC);

            // Check if the user has completed the previous content (ID 23)
            $quiz_progress_query = $conn->prepare("SELECT * FROM `user_progress` WHERE user_id = :user_id AND content_id = 3");
            $quiz_progress_query->bindParam(':user_id', $user_id);
            $quiz_progress_query->execute();
            $quiz_progress = $quiz_progress_query->fetch(PDO::FETCH_ASSOC);

            if ($quiz_progress && $quiz_progress['is_completed'] == 1) {
               // User has completed the previous content, show the quiz link
               echo '<a href="Reading 2.php" class="inline-btn">Read Now</a>';
            } else {
               // User has not completed the previous content, show a message
               echo '<p>You need to complete the previous section to access this reading.</p>';
            }
         } else {
            echo "<p>No reading found.</p>";
         }
         ?>
      </div>
   

      <?php
      // Fetch the reading content or video (ID 23 as example, replace if different content ID)
      $content_query = $conn->prepare("SELECT * FROM `content` WHERE id = 34");
      $content_query->execute();

      // Check if the content exists
      if ($content_query->rowCount() > 0) {
         $content = $content_query->fetch(PDO::FETCH_ASSOC); // Fetch content row
         
         // Check if the user is logged in
         if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];

            // Query to check if the user has completed this content (ID 23)
            $progress_query = $conn->prepare("SELECT * FROM `user_progress` WHERE user_id = :user_id AND content_id = 4");
            $progress_query->bindParam(':user_id', $user_id);
            $progress_query->execute();
            $progress = $progress_query->fetch(PDO::FETCH_ASSOC);
            
            // If the user has completed the content
            if ($progress && $progress['is_completed'] == 1) {
               // Unlock next content (i.e., the quiz or the next video/document)
               $next_content_query = $conn->prepare("SELECT * FROM `course_sequence` WHERE `order` = 5"); // Assuming 2 is the next content order
               $next_content_query->execute();
               if ($next_content_query->rowCount() > 0) {
                  $next_content = $next_content_query->fetch(PDO::FETCH_ASSOC);
                  
                  // Check if next content is a video or quiz, and update the content ID
                  if ($next_content['module_type'] == 'video') {
                     echo '
                     <div class="box">
                        <div class="thumb">
                           <img src="' . htmlspecialchars($content['thumb']) . '" alt="Thumbnail">
                           <span>' . htmlspecialchars($content['duration']) . ' minutes</span>
                        </div>
                        <h3 class="title">' . htmlspecialchars($next_content['module_title']) . '</h3>
                        <a href="watch-video3.php?get_id=34" class="inline-btn">View Video</a>
                     </div>';
                  } elseif ($next_content['module_type'] == 'quiz') {
                     echo '
                     <div class="box">
                        <div class="thumb">
                           <img src="images/easylevel.jpg" alt="">
                        </div>
                        <h3 class="title">' . htmlspecialchars($next_content['module_title']) . '</h3>
                        <a href="Module 2 Quiz.php" class="inline-btn">Take Quiz</a>
                     </div>';
                  }
               }
            } else {
               // If the user has not completed the content, show the locked message
               echo '
               <div class="box">
                  <div class="thumb">
                     <img src="' . htmlspecialchars($content['thumb']) . '" alt="Thumbnail">
                     <span>' . htmlspecialchars($content['duration']) . ' minutes</span>
                  </div>
                  <h3 class="title">' . htmlspecialchars($content['title']) . '</h3>
                  <p class="locked">Complete the previous section to unlock this content.</p>
               </div>';
            }
         } 
      } else {
         echo "<p>No content found.</p>";
      }
      ?>

      <!-- Quiz Section (Only visible if the previous content is completed) -->
      <div class="box">
         <div class="thumb">
            <img src="images/mod2quiz.jpg" alt="">
         </div>
         <h3 class="title">Component tester Module 2 Quiz with Marlin fish</h3>

         <?php
         $quiz_query = $conn->prepare("SELECT * FROM `content` WHERE id = 6");
         $quiz_query->execute();

         if ($quiz_query->rowCount() > 0) {
            $quiz = $quiz_query->fetch(PDO::FETCH_ASSOC);

            // Check if the user has completed the previous content (ID 23)
            $quiz_progress_query = $conn->prepare("SELECT * FROM `user_progress` WHERE user_id = :user_id AND content_id = 5");
            $quiz_progress_query->bindParam(':user_id', $user_id);
            $quiz_progress_query->execute();
            $quiz_progress = $quiz_progress_query->fetch(PDO::FETCH_ASSOC);

            if ($quiz_progress && $quiz_progress['is_completed'] == 1) {
               // User has completed the previous content, show the quiz link
               echo '<a href="Module 2 Quiz.php" class="inline-btn">Take Quiz</a>';
            } else {
               // User has not completed the previous content, show a message
               echo '<p>You need to complete the previous section to access this quiz.</p>';
            }
         } else {
            echo "<p>No quiz found.</p>";
         }
         ?>
      </div>
   </div>

   </div>
</section>


<!-- Reading Document Section -->
<section class="courses">
   <h1 class="heading">Component Tester Module 3</h1>

   <div class="box-container">
   <div class="box">
         <div class="thumb">
            <img src="images/CT1 thumb.jpg" alt="">
         </div>
         <h3 class="title">Reading Document 3: Introduction to DIY Component Tester</h3>

         <?php
         $quiz_query = $conn->prepare("SELECT * FROM `content` WHERE id = 7");
         $quiz_query->execute();

         if ($quiz_query->rowCount() > 0) {
            $quiz = $quiz_query->fetch(PDO::FETCH_ASSOC);

            // Check if the user has completed the previous content (ID 23)
            $quiz_progress_query = $conn->prepare("SELECT * FROM `user_progress` WHERE user_id = :user_id AND content_id = 6");
            $quiz_progress_query->bindParam(':user_id', $user_id);
            $quiz_progress_query->execute();
            $quiz_progress = $quiz_progress_query->fetch(PDO::FETCH_ASSOC);

            if ($quiz_progress && $quiz_progress['is_completed'] == 1) {
               // User has completed the previous content, show the quiz link
               echo '<a href="Reading 3.php" class="inline-btn">Read Now</a>';
            } else {
               // User has not completed the previous content, show a message
               echo '<p>You need to complete the previous section to access this reading.</p>';
            }
         } else {
            echo "<p>No document found.</p>";
         }
         ?>
      </div>
   

      <?php
      // Fetch the reading content or video (ID 23 as example, replace if different content ID)
      $content_query = $conn->prepare("SELECT * FROM `content` WHERE id = 33");
      $content_query->execute();

      // Check if the content exists
      if ($content_query->rowCount() > 0) {
         $content = $content_query->fetch(PDO::FETCH_ASSOC); // Fetch content row
         
         // Check if the user is logged in
         if (isset($_COOKIE['user_id'])) {
            $user_id = $_COOKIE['user_id'];

            // Query to check if the user has completed this content (ID 23)
            $progress_query = $conn->prepare("SELECT * FROM `user_progress` WHERE user_id = :user_id AND content_id = 7");
            $progress_query->bindParam(':user_id', $user_id);
            $progress_query->execute();
            $progress = $progress_query->fetch(PDO::FETCH_ASSOC);
            
            // If the user has completed the content
            if ($progress && $progress['is_completed'] == 1) {
               // Unlock next content (i.e., the quiz or the next video/document)
               $next_content_query = $conn->prepare("SELECT * FROM `course_sequence` WHERE `order` = 8"); // Assuming 2 is the next content order
               $next_content_query->execute();
               if ($next_content_query->rowCount() > 0) {
                  $next_content = $next_content_query->fetch(PDO::FETCH_ASSOC);
                  
                  // Check if next content is a video or quiz, and update the content ID
                  if ($next_content['module_type'] == 'video') {
                     echo '
                     <div class="box">
                        <div class="thumb">
                           <img src="' . htmlspecialchars($content['thumb']) . '" alt="Thumbnail">
                           <span>' . htmlspecialchars($content['duration']) . ' minutes</span>
                        </div>
                        <h3 class="title">' . htmlspecialchars($next_content['module_title']) . '</h3>
                        <a href="watch-video4.php?get_id=33" class="inline-btn">View Video</a>
                     </div>';
                  } elseif ($next_content['module_type'] == 'quiz') {
                     echo '
                     <div class="box">
                        <div class="thumb">
                           <img src="images/mod3quiz.jpg" alt="">
                        </div>
                        <h3 class="title">' . htmlspecialchars($next_content['module_title']) . '</h3>
                        <a href="Module 3 Quiz.php" class="inline-btn">Take Quiz</a>
                     </div>';
                  }
               }
            } else {
               // If the user has not completed the content, show the locked message
               echo '
               <div class="box">
                  <div class="thumb">
                     <img src="' . htmlspecialchars($content['thumb']) . '" alt="Thumbnail">
                     <span>' . htmlspecialchars($content['duration']) . ' minutes</span>
                  </div>
                  <h3 class="title">' . htmlspecialchars($content['title']) . '</h3>
                  <p class="locked">Complete the previous section to unlock this content.</p>
               </div>';
            }
         } 
      } else {
         echo "<p>No content found.</p>";
      }
      ?>

<!-- Quiz Section (Only visible if the previous content is completed) -->
<div class="box">
         <div class="thumb">
            <img src="images/mod3quiz.jpg" alt="">
         </div>
         <h3 class="title">Component Tester Module 3 Quiz with Danny Dino</h3>

         <?php
         $quiz_query = $conn->prepare("SELECT * FROM `content` WHERE id = 9");
         $quiz_query->execute();

         if ($quiz_query->rowCount() > 0) {
            $quiz = $quiz_query->fetch(PDO::FETCH_ASSOC);

            // Check if the user has completed the previous content (ID 23)
            $quiz_progress_query = $conn->prepare("SELECT * FROM `user_progress` WHERE user_id = :user_id AND content_id = 8");
            $quiz_progress_query->bindParam(':user_id', $user_id);
            $quiz_progress_query->execute();
            $quiz_progress = $quiz_progress_query->fetch(PDO::FETCH_ASSOC);

            if ($quiz_progress && $quiz_progress['is_completed'] == 1) {
               // User has completed the previous content, show the quiz link
               echo '<a href="Module 3 Quiz.php" class="inline-btn">Take Quiz</a>';
            } else {
               // User has not completed the previous content, show a message
               echo '<p>You need to complete the previous section to access this quiz.</p>';
            }
         } else {
            echo "<p>No quiz found.</p>";
         }
         ?>
      </div>
   </div>

   </div>
</section>


<section class="courses">
   <h1 class="heading">Component Tester additional resources</h1>

   <div class="box-container">

      <div class="box">
         <div class="thumb">
            <img src="images/keychainCT.webp" alt="">
         </div>
         <h3 class="title">Instructables: Component Tester in a keychain</h3>
         <a href="https://www.instructables.com/Component-Tester-in-a-Keychain/" class="inline-btn">Read now</a>
      </div>

      <?php
      // Fetch the video with id 23 from the 'content' table
      $videos_query = $conn->prepare("SELECT * FROM `content` WHERE id = 24");
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

      <div class="box">
         <div class="thumb">
            <img src="images/Werner.webp" alt="">
         </div>
         <h3 class="title">Ecorobotics: How to build a simple arduino component tester</h3>
         <a href="https://www.ecorobotics.com.na/blogs/news/how-to-build-a-simple-arduino-component-tester?srsltid=AfmBOoqI-P8jVlVMvO0qqF14IkRNM2tQcwMY3hetTmR2kX4L5pvSNE0m" class="inline-btn">Read now</a>
      </div>

      <div class="box">
        <div class="thumb">
           <img src="images/xtra.jpg" alt="">
        </div>
        <h3 class="title">Extra Quiz Questions</h3>
        <a href="Medium CT assessment.php" class="inline-btn">Take Quiz</a>
     </div>
   </div>

   </div>

</section>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
