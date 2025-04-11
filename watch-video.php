<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

// Get the video ID from the URL
if (isset($_GET['get_id'])) {
    $get_id = $_GET['get_id']; // The ID of the clicked video
} else {
    // If there's no ID in the URL, handle it appropriately (e.g., show an error)
    die("Video ID is missing.");
}

// Fetch video details from the database using the hardcoded get_id
$video_query = $conn->prepare("SELECT * FROM `content` WHERE id = :get_id");
$video_query->bindParam(':get_id', $get_id, PDO::PARAM_INT); // Bind hardcoded value to the query

$video_query->execute();

if ($video_query->rowCount() > 0) {
    $video = $video_query->fetch(PDO::FETCH_ASSOC);
} else {
    die("Video not found.");
}

// Handle comment submission
if (isset($_POST['submit_comment'])) {
    $comment = $_POST['comment'];

    if ($user_id != '') {
        // Insert the comment into the database
        $insert_comment = $conn->prepare("INSERT INTO `comments` (user_id, content_id, comment, date) VALUES (?, ?, ?, NOW())");
        $insert_comment->execute([$user_id, $get_id, $comment]);

        // Prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit;
    } else {
        echo "<script>alert('You need to be logged in to submit a comment.');</script>";
    }
}

if(isset($_POST['add_comment'])){

   if($user_id != ''){

      $id = unique_id();
      $comment_box = $_POST['comment_box'];
      $comment_box = filter_var($comment_box, FILTER_SANITIZE_STRING);
      $content_id = $_POST['content_id'];
      $content_id = filter_var($content_id, FILTER_SANITIZE_STRING);

      $select_content = $conn->prepare("SELECT * FROM `content` WHERE id = ? LIMIT 1");
      $select_content->execute([$content_id]);
      $fetch_content = $select_content->fetch(PDO::FETCH_ASSOC);

      $tutor_id = $fetch_content['tutor_id'];

      if($select_content->rowCount() > 0){

         $select_comment = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ? AND user_id = ? AND comment = ?");
         $select_comment->execute([$content_id, $user_id, $comment_box]);

         if($select_comment->rowCount() > 0){
            $message[] = 'comment already added!';
         }else{
            $insert_comment = $conn->prepare("INSERT INTO `comments`(id, content_id, user_id, comment) VALUES(?,?,?,?)");
            $insert_comment->execute([$id, $content_id, $user_id, $comment_box]);
            $message[] = 'new comment added!';
         }

      }else{
         $message[] = 'something went wrong!';
      }

   }else{
      $message[] = 'please login first!';
   }

}

if(isset($_POST['delete_comment'])){

   $delete_id = $_POST['comment_id'];
   $delete_id = filter_var($delete_id, FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ?");
   $verify_comment->execute([$delete_id]);

   if($verify_comment->rowCount() > 0){
      $delete_comment = $conn->prepare("DELETE FROM `comments` WHERE id = ?");
      $delete_comment->execute([$delete_id]);
      $message[] = 'comment deleted successfully!';
   }else{
      $message[] = 'comment already deleted!';
   }

}

if(isset($_POST['update_now'])){

   $update_id = $_POST['update_id'];
   $update_id = filter_var($update_id, FILTER_SANITIZE_STRING);
   $update_box = $_POST['update_box'];
   $update_box = filter_var($update_box, FILTER_SANITIZE_STRING);

   $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? AND comment = ?");
   $verify_comment->execute([$update_id, $update_box]);

   if($verify_comment->rowCount() > 0){
      $message[] = 'comment already added!';
   }else{
      $update_comment = $conn->prepare("UPDATE `comments` SET comment = ? WHERE id = ?");
      $update_comment->execute([$update_box, $update_id]);
      $message[] = 'comment edited successfully!';
   }

}
?>

<?php
if (isset($_POST['bookmark'])) {
    // Check if the user is logged in
    if (isset($_COOKIE['user_id'])) {
        $user_id = $_COOKIE['user_id'];
    } else {
        // Redirect to the home page if the user is not logged in
        header('location: home.php');
        exit();
    }

    // Get the video_id from the form submission
    $video_id = $_POST['video_id'];

    // Check if the video is already bookmarked
    $check_existing = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
    $check_existing->execute([$user_id, $video_id]);

    // If already bookmarked, remove the bookmark
    if ($check_existing->rowCount() > 0) {
        // Remove the bookmark
        $delete_bookmark = $conn->prepare("DELETE FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
        $delete_bookmark->execute([$user_id, $video_id]);
        echo '<script>alert("Bookmark removed!");</script>';
    } else {
        // If not bookmarked, insert the bookmark into the database
        $insert_bookmark = $conn->prepare("INSERT INTO `bookmark` (`user_id`, `playlist_id`) VALUES (?, ?)");
        $insert_bookmark->execute([$user_id, $video_id]);
        echo '<script>alert("Video saved to bookmarks!");</script>';
    }
}
?>

<?php
// Check if the video is already bookmarked
$check_existing = $conn->prepare("SELECT * FROM `bookmark` WHERE user_id = ? AND playlist_id = ?");
$check_existing->execute([$user_id, $video['id']]);

// If already bookmarked, show "Remove" option, else show "Save"
if ($check_existing->rowCount() > 0) {
    $button_label = "Remove";
    $button_icon = "fas fa-bookmark";  // Filled icon for removed bookmark
} else {
    $button_label = "Save";
    $button_icon = "far fa-bookmark";  // Empty icon for saved bookmark
}
?>


<?php
   if(isset($_POST['edit_comment'])){
      $edit_id = $_POST['comment_id'];
      $edit_id = filter_var($edit_id, FILTER_SANITIZE_STRING);
      $verify_comment = $conn->prepare("SELECT * FROM `comments` WHERE id = ? LIMIT 1");
      $verify_comment->execute([$edit_id]);
      if($verify_comment->rowCount() > 0){
         $fetch_edit_comment = $verify_comment->fetch(PDO::FETCH_ASSOC);
?>
<section class="edit-comment">
   <h1 class="heading">edit comment</h1>
   <form action="" method="post">
      <input type="hidden" name="update_id" value="<?= $fetch_edit_comment['id']; ?>">
      <textarea name="update_box" class="box" maxlength="1000" required placeholder="please enter your comment" cols="30" rows="10"><?= $fetch_edit_comment['comment']; ?></textarea>
      <div class="flex">
         <a href="watch_video.php?get_id=<?= $get_id; ?>" class="inline-option-btn">cancel edit</a>
         <input type="submit" value="update now" name="update_now" class="inline-btn">
      </div>
   </form>
</section>
<?php
   }else{
      $message[] = 'comment was not found!';
   }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title><?= htmlspecialchars($video['title']); ?></title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="watch-video"> 
   <div class="video-container">
      <div class="video">
        <iframe width="900" height="530" src="<?= htmlspecialchars($video['embeded_video']); ?>" 
        title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; 
        picture-in-picture; web-share" referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
      </div>
      <h3 class="title"><?= htmlspecialchars($video['title']); ?></h3>
      <div class="tutor">
         <img src="<?= htmlspecialchars($video['tutor_thumb']); ?>" alt="">
         <div>
            <h3><?= htmlspecialchars($video['tutor_id']); ?></h3>
            <span>Youtube</span>
         </div>
      </div>
      <form action="" method="post" class="flex">
         <a href="<?= htmlspecialchars($video['tutor_playlist']); ?>" class="inline-btn">view creator</a>
         <input type="hidden" name="video_id" value="<?= $video['id']; ?>"> <!-- Pass the video_id -->
         <button type="submit" name="bookmark">
        <i class="<?= $button_icon; ?>"></i><span><?= $button_label; ?></span>
    </button>
      </form>
      <p class="description">
        <?= nl2br(htmlspecialchars($video['description'])); ?>
      </p>

   </div>
</section>

<section class="playlist-videos">
    <h1 class="heading">Recommended videos</h1>
    <div class="box-container">
        <?php
        // Get the current video ID
        $current_video_id = $get_id;

        // Initialize an array to store video IDs to be fetched
        $video_ids = [];

        // 1. Get the next video (ID + 1)
        $video_ids[] = $current_video_id + 1;

        // 2. Get the previous video (ID - 1)
        $video_ids[] = $current_video_id - 1;

        // 3. Get a random video (excluding the current one)
        $video_ids[] = 1;  // This will be used for the random query

        // Prepare the query to fetch videos
        $placeholders = implode(",", array_fill(0, count($video_ids), "?"));
        $playlist_query = $conn->prepare("SELECT * FROM `content` WHERE id IN ($placeholders) AND id != ? LIMIT 3");

        // Execute the query with the current video ID and the video IDs array
        $video_ids[] = $current_video_id; // Exclude the current video from the random query
        $playlist_query->execute($video_ids);

        if ($playlist_query->rowCount() > 0) {
            while ($playlist_video = $playlist_query->fetch(PDO::FETCH_ASSOC)) {
        ?>
        <a href="watch-video.php?get_id=<?= $playlist_video['id']; ?>" class="box button">
            <i class="fas fa-play"></i>
            <img src="<?= htmlspecialchars($playlist_video['thumb']); ?>" alt="">
            <h3><?= htmlspecialchars($playlist_video['title']); ?></h3>
        </a>
        <?php
            }
        } else {
            echo '<p class="empty">No recommended videos found.</p>';
        }
        ?>
    </div>
</section>


<!-- comments section starts  -->

<section class="comments">

   <h1 class="heading">add a comment</h1>

   <form action="" method="post" class="add-comment">
      <input type="hidden" name="content_id" value="<?= $get_id; ?>">
      <textarea name="comment_box" required placeholder="write your comment..." maxlength="1000" cols="30" rows="10"></textarea>
      <input type="submit" value="add comment" name="add_comment" class="inline-btn">
   </form>

   <h1 class="heading">user comments</h1>

   
   <div class="show-comments">
      <?php
         $select_comments = $conn->prepare("SELECT * FROM `comments` WHERE content_id = ?");
         $select_comments->execute([$get_id]);
         if($select_comments->rowCount() > 0){
            while($fetch_comment = $select_comments->fetch(PDO::FETCH_ASSOC)){   
               $select_commentor = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
               $select_commentor->execute([$fetch_comment['user_id']]);
               $fetch_commentor = $select_commentor->fetch(PDO::FETCH_ASSOC);
      ?>
      <div class="box" style="<?php if($fetch_comment['user_id'] == $user_id){echo 'order:-1;';} ?>">
         <div class="user">
            <img src="uploaded_files/<?= $fetch_commentor['image']; ?>" alt="">
            <div>
               <h3><?= $fetch_commentor['name']; ?></h3>
               <span><?= $fetch_comment['date']; ?></span>
            </div>
         </div>
         <p class="text"><?= $fetch_comment['comment']; ?></p>
         <?php
            if($fetch_comment['user_id'] == $user_id){ 
         ?>
         <form action="" method="post" class="flex-btn">
            <input type="hidden" name="comment_id" value="<?= $fetch_comment['id']; ?>">
            <button type="submit" name="edit_comment" class="inline-option-btn">edit comment</button>
            <button type="submit" name="delete_comment" class="inline-delete-btn" onclick="return confirm('delete this comment?');">delete comment</button>
         </form>
         <?php
         }
         ?>
      </div>
      <?php
       }
      }else{
         echo '<p class="empty">no comments added yet!</p>';
      }
      ?>
      </div>
   
</section>
<!-- Custom JS file link -->
<script src="js/script.js"></script>
   
</body>
</html>
