<?php
session_start();
include('components/connect.php');  // Include database connection

// Ensure the user is logged in and video_id is passed in the URL
if (isset($_GET['video_id']) && isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id']; // User ID from session
    $video_id = $_GET['video_id'];  // Video ID from the URL

    // Insert or update the user's progress for the video
    $sql = "INSERT INTO user_progress (user_id, video_id, is_completed)
            VALUES (?, ?, 1) 
            ON DUPLICATE KEY UPDATE is_completed = 1";  // Mark as completed if already present
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ii', $user_id, $video_id); // Bind user_id (int), video_id (int)

    if ($stmt->execute()) {
        // Redirect to the video or course page
        header("Location: index.php?video_id=$video_id"); 
        exit;
    } else {
        echo "Error: Could not update progress.";
    }
} else {
    echo "Invalid request.";
}
?>
