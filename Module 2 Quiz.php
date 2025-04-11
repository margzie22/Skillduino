<?php
require 'components/connect.php'; // Ensure you have the correct database connection

// Prepare the query safely
$stmt = $conn->prepare("SELECT * FROM quiz_questions WHERE difficulty = :difficulty");
$stmt->execute(['difficulty' => 'easy']);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all questions as an associative array

if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
 }else{
    $user_id = '';
 }


// Get the content ID for this section (this would be specific to "Reading 1")
$content_id = 6; // Assuming content_id 1 corresponds to Reading 1

// Check if the "Mark as Completed" button is clicked
if (isset($_POST['mark_completed'])) {
    if ($user_id != '') {
        // Check if the user has already completed this section
        $progress_check = $conn->prepare("SELECT * FROM `user_progress` WHERE user_id = :user_id AND content_id = :content_id");
        $progress_check->bindParam(':user_id', $user_id);
        $progress_check->bindParam(':content_id', $content_id);
        $progress_check->execute();

        if ($progress_check->rowCount() == 0) {
            // If not completed, mark it as completed in the database
            $query = $conn->prepare("INSERT INTO `user_progress` (user_id, content_id, is_completed) VALUES (:user_id, :content_id, 1)");
            $query->bindParam(':user_id', $user_id);
            $query->bindParam(':content_id', $content_id);
            $query->execute();
        } else {
            // If already completed, update the status to completed
            $query = $conn->prepare("UPDATE `user_progress` SET is_completed = 1 WHERE user_id = :user_id AND content_id = :content_id");
            $query->bindParam(':user_id', $user_id);
            $query->bindParam(':content_id', $content_id);
            $query->execute();
        }
    }
}

// Fetch the content of this specific module (Reading 1)
$reading_query = $conn->prepare("SELECT * FROM `course_sequence` WHERE content_id = :content_id");
$reading_query->bindParam(':content_id', $content_id);
$reading_query->execute();

if ($reading_query->rowCount() > 0) {
    $reading = $reading_query->fetch(PDO::FETCH_ASSOC); // Fetch the Reading 1 data
} else {
    die("Reading module not found.");
}

// Fetch the next module (e.g., Video 1) that will be unlocked once the user completes this one
$next_module_query = $conn->prepare("SELECT * FROM `course_sequence` WHERE `order` = :next_order LIMIT 1");
$next_order = $content_id + 1; // Next module in the sequence (assuming sequential order)
$next_module_query->bindParam(':next_order', $next_order);
$next_module_query->execute();

if ($next_module_query->rowCount() > 0) {
    $next_module = $next_module_query->fetch(PDO::FETCH_ASSOC); // Fetch the next module data (e.g., Video 1)
} else {
    $next_module = null; // No next module found, maybe this is the last module
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Beginner Quiz</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>


<section class="quiz">
  <div class="quiz-container">
    <h3 class="heading">Component tester Module 2 Quiz</h3>
    <div class="tutor">
      <iframe id="scratch-game" src="https://scratch.mit.edu/projects/1155997812/embed" 
              allowtransparency="true" width="750" height="650" frameborder="0" scrolling="no" allowfullscreen>
      </iframe>
<?php


// Check if the form has been submitted (i.e., the user pressed "Mark as Completed")
if (isset($_POST['mark_completed'])) {
    // Mark this module as completed by setting a session variable
    $_SESSION['module_completed'] = true;
}

// Check if the user has completed the module and display the "Next Module" button
if (isset($_SESSION['module_completed']) && $_SESSION['module_completed'] === true) {
    // Assuming you have a variable $next_module with next module info
    if (isset($next_module)) {
        echo '<section class="next-module-section">';
        echo '<h3>Next Module: </h3>';
        echo '<a href="' . htmlspecialchars($next_module['module_url']) . '" class="inline-btn">Start Next: ' . htmlspecialchars($next_module['module_title']) . '</a>';
        echo '</section>';
    } else {
        echo '<p>This is the last module. Well done!</p>';
    }
} else {
    // Show the comment and the "Mark as Completed" button if the user hasn't completed the module yet
    echo '<section class="mark-completed-section">';
    
    // Comment or message to explain the unlocking mechanism
    echo '<p>Click "Mark as Completed" to unlock the next stage!</p>';
    
    // The "Mark as Completed" form button
    echo '<form method="POST">';
    echo '<input type="submit" name="mark_completed" value="Mark as Completed" class="inline-btn">';
    echo '</form>';
    
    echo '</section>';
}
?>
</section>















<!-- custom js file link  -->
<script src="js/script.js"></script>

   
</body>
</html>