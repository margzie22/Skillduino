<?php
session_start();
include('components/connect.php'); // Include database connection

// Assume the user is logged in and we have their user_id in session
$user_id = $_SESSION['user_id']; 

// Fetch all content (videos) from the content table
$sql = "SELECT * FROM content";
$result = $conn->query($sql);

// Fetch user's progress for each video
$user_progress = [];
$sql_progress = "SELECT video_id, is_completed FROM user_progress WHERE user_id = ?";
$stmt = $conn->prepare($sql_progress);
$stmt->bind_param('i', $user_id);  // Bind user_id (int)
$stmt->execute();
$progress_result = $stmt->get_result();
while ($row = $progress_result->fetch_assoc()) {
    $user_progress[$row['video_id']] = $row['is_completed']; // Store progress for each video
}

// Display each video with progress
while ($video = $result->fetch_assoc()) {
    $video_id = $video['id'];  // Get video id from content table
    $is_completed = isset($user_progress[$video_id]) && $user_progress[$video_id] == 1;  // Check if video is completed
    ?>
    <div class="video-section">
        <h3><?= htmlspecialchars($video['title']); ?></h3>
        <p><?= htmlspecialchars($video['description']); ?></p>

        <?php if ($is_completed): ?>
            <p>Video Completed!</p>
        <?php else: ?>
            <a href="mark_complete.php?video_id=<?= htmlspecialchars($video['id']); ?>" class="inline-btn">Mark as Completed</a>
        <?php endif; ?>
    </div>
    <?php
}
?>

