<?php
require 'components/connect.php'; // Include database connection

$score = 0;
$difficulty = $_POST['difficulty'] ?? 'beginner';

// Fetch questions from the database
$stmt = $conn->prepare("SELECT * FROM quiz_questions WHERE difficulty = :difficulty");
$stmt->execute(['difficulty' => $difficulty]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Calculate the score
foreach ($questions as $row) {
    $question_id = $row['id'];
    $correct_answer = $row['correct_option'];

    if (isset($_POST["answer_$question_id"]) && $_POST["answer_$question_id"] == $correct_answer) {
        $score++;
    }
}

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
   <title>Quiz Results</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php include 'components/user_header.php'; ?>

<section class="courses">
    <div class="box-container">
        <div class="box">
            <h3 class="title">Quiz Completed!</h3>
            <h3 class="title">Your Score: <strong><?php echo $score . "/" . count($questions); ?></strong></h3>

            <?php
            // Calculate the percentage score
            $percentage = ($score / count($questions)) * 100;

            // Check if score is above 70%
            if ($percentage >= 70) {
                echo "<h3 class='title' style='color: green;'>You Passed!</h3>";
            } else {
                echo "<h3 class='title' style='color: red;'>Please Try Again</h3>";
            }
            ?>

            <button class="inline-btn">
                <a href="Component tester project.php" style="color: white;">Return to Courses</a>
            </button>
        </div>
    </div>
</section>



</body>
</html>


















<!-- custom js file link  -->
<script src="js/script.js"></script>

   
</body>
</html>