<?php
require 'components/connect.php'; // Ensure you have the correct database connection

// Prepare the query safely
$stmt = $conn->prepare("SELECT * FROM quiz_questions WHERE difficulty = :difficulty");
$stmt->execute(['difficulty' => 'advanced']);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC); // Fetch all questions as an associative array

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
   <title>Advanced Quiz</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="watch-video">

   <div class="video-container">
<h3 class="heading">Advanced level quiz</h3>
<div class="tutor">
<form method="POST" action="submit_quiz.php">
        <?php if ($questions): ?>
            <?php $questionNumber = 1; // Start question numbering ?>
            <?php foreach ($questions as $row): ?>
                <h3><?php echo $questionNumber . ". " . htmlspecialchars($row['question']); ?></h3>
                <span>
                    <input type="radio" name="answer_<?php echo $row['id']; ?>" value="A"> <?php echo htmlspecialchars($row['option_a']); ?><br>
                    <input type="radio" name="answer_<?php echo $row['id']; ?>" value="B"> <?php echo htmlspecialchars($row['option_b']); ?><br>
                    <input type="radio" name="answer_<?php echo $row['id']; ?>" value="C"> <?php echo htmlspecialchars($row['option_c']); ?><br>
                    <input type="radio" name="answer_<?php echo $row['id']; ?>" value="D"> <?php echo htmlspecialchars($row['option_d']); ?><br>
                </span>
                <?php $questionNumber++; // Increment question number ?>
            <?php endforeach; ?>
            <input type="hidden" name="difficulty" value="advanced">
            <button type="submit" class="inline-btn">Submit</button>
        <?php else: ?>
            <p>No questions available for this level.</p>
        <?php endif; ?>
</form>
</div>
</section>














<!-- custom js file link  -->
<script src="js/script.js"></script>

   
</body>
</html>