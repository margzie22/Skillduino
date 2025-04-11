<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
 }else{
    $user_id = '';
 }

// Check if the 'id' parameter exists in the URL
if (isset($_GET['id'])) {
    $component_id = $_GET['id'];

    // Fetch the component details from the database using the provided ID
    $query = $conn->prepare("SELECT * FROM components WHERE id = :id");
    $query->bindParam(':id', $component_id, PDO::PARAM_INT);
    $query->execute();

    // Check if the component exists
    if ($query->rowCount() > 0) {
        $component = $query->fetch(PDO::FETCH_ASSOC); // Fetch the component row
    } else {
        $error_message = "Component not found.";
    }
} else {
    $error_message = "No component ID provided.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Component Information</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="playlist-details">
   <h1 class="heading">Component Details</h1>

   <div class="row">

      <div class="column">
         <?php
         if (isset($component)) {
         ?>
            <div class="thumb">
               <img src="images/<?= htmlspecialchars($component['image']); ?>" alt="<?= htmlspecialchars($component['name']); ?>">
            </div>
         <?php
         }
         ?>
      </div>

      <div class="column">
         <div class="details">
            <?php
            if (isset($component)) {
            ?>
               <h3><?= htmlspecialchars($component['name']); ?></h3>
               <p><?= nl2br(htmlspecialchars($component['description'])); ?></p>

            </form>
            <?php
            } else {
               echo "<p>" . htmlspecialchars($error_message) . "</p>";
            }
            ?>
         </div>
      </div>

   </div>
</section>

<?php
// Fetch random components from the database
$query = $conn->prepare("SELECT * FROM components ORDER BY RAND() LIMIT 3");
$query->execute();
$random_components = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<section class="playlist-videos">
   <h1 class="heading">Recommended Components</h1>

   <div class="box-container">
      <?php foreach ($random_components as $component): ?>
         <a href="component-info.php?id=<?= htmlspecialchars($component['id']); ?>" class="box">
            <i class="fas fa-play"></i>
            <img src="images/<?= htmlspecialchars($component['image']); ?>" alt="<?= htmlspecialchars($component['name']); ?>">
            <h3><?= htmlspecialchars($component['name']); ?></h3>
         </a>
      <?php endforeach; ?>
   </div>
</section>

<!-- custom js file link  -->
<script src="js/script.js"></script>

</body>
</html>
