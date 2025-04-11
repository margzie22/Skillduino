<?php
include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   $user_id = '';
}

// Fetch component details from the database
$component_id = 1;  // Example, you can dynamically get this from the URL or query string
$sql = "SELECT * FROM components WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $component_id);
$stmt->execute();
$result = $stmt->get_result();

// Check if a component was found
if($result->num_rows > 0) {
   $component = $result->fetch_assoc();
} else {
   $component = null;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Watch Info</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="playlist-details">
   <h1 class="heading">Component details</h1>

   <div class="row">
      <div class="column">
         <div class="thumb">
            <?php if ($component): ?>
               <img src="images/<?php echo htmlspecialchars($component['image']); ?>" alt="">
            <?php else: ?>
               <p>Component not found.</p>
            <?php endif; ?>
         </div>

         <form action="" method="post" class="save-playlist">
            <button type="submit"><i class="far fa-bookmark"></i> <span>Bookmark</span></button>
         </form>
      </div>
      <div class="column">
         <div class="details">
            <?php if ($component): ?>
               <h3><?php echo htmlspecialchars($component['name']); ?></h3>
               <p><?php echo nl2br(htmlspecialchars($component['description'])); ?></p>
               <a href="<?php echo htmlspecialchars($component['video_url']); ?>" class="inline-btn">view video</a>
            <?php else: ?>
               <p>No component details available.</p>
            <?php endif; ?>
         </div>
      </div>
   </div>

</section>

<section class="playlist-videos">
   <h1 class="heading">Recommended components</h1>

   <div class="box-container">

      <a class="box">
         <i class="fas fa-play"></i>
         <img src="images/thumb-2.png" alt="">
         <h3>Resistor</h3>
      </a>

      <a href="Breadboardinfo.html" class="box button">
         <i class="fas fa-play"></i>
         <img src="images/thumb-3.png" alt="">
         <h3>Breadboard</h3>
      </a>

      <a class="box">
         <i class="fas fa-play"></i>
         <img src="images/thumb-4.jpg" alt="">
         <h3>Wires</h3>
      </a>
   </div>

</section>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
