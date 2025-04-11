<?php
include 'components/connect.php'; // Include the database connection

// Check if the user is logged in and retrieve user_id from cookie
if(isset($_COOKIE['user_id'])){
    $user_id = $_COOKIE['user_id'];
}else{
    $user_id = '';
    // If the user is not logged in, you can redirect them to the login page
    header("Location: login.php");
    exit();
}

// Get the raw POST data
$data = json_decode(file_get_contents("php://input"), true);

// Check if the required data is present
if (isset($data['score']) && isset($data['user_id'])) {
    $userScore = $data['score'];
    $userId = $data['user_id'];

    // Check if the user already has a score recorded
    $sql = "SELECT MAX(score) AS highest_score FROM scores WHERE user_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    // If no score is found or the new score is higher, update or insert it
    if ($row && $row['highest_score'] < $userScore) {
        // Update the highest score
        $updateSql = "UPDATE scores SET score = ? WHERE user_id = ?";
        $updateStmt = $conn->prepare($updateSql);
        $updateStmt->bind_param("is", $userScore, $userId);
        $updateStmt->execute();
        $updateStmt->close();
        echo json_encode(['status' => 'success', 'message' => 'High score updated!']);
    } else {
        // Insert a new record if no score exists
        $insertSql = "INSERT INTO scores (user_id, score) VALUES (?, ?)";
        $insertStmt = $conn->prepare($insertSql);
        $insertStmt->bind_param("si", $userId, $userScore);
        $insertStmt->execute();
        $insertStmt->close();
        echo json_encode(['status' => 'success', 'message' => 'Score saved!']);
    }

    // Close the statement and connection
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Missing data']);
}

// Close the connection
$conn->close();
?>
