<?php
// comment.php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['user_id'])) {
    $video_id = $_POST['video_id'];
    $user_id = $_SESSION['user_id'];
    $content = $_POST['content'];

    $stmt = $pdo->prepare("INSERT INTO comments (user_id, video_id, content) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $video_id, $content]);
}

header("Location: video.php?id=" . $_POST['video_id']);
exit;
?>
