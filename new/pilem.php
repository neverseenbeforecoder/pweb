<?php
// video.php
session_start();
require 'db.php';

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare("SELECT * FROM videos WHERE id = ?");
$stmt->execute([$id]);
$video = $stmt->fetch();

// Ambil komentar
$stmt = $pdo->prepare("SELECT c.*, u.username FROM comments c JOIN users u ON c.user_id = u.id WHERE video_id = ? ORDER BY c.created_at DESC");
$stmt->execute([$id]);
$comments = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Tonton Film</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #121212;
      color: white;
    }

    .btn-back-icon {
      position: fixed;
      top: 20px;
      left: 20px;
      font-size: 1.5rem;
      color: #aaa;
      text-decoration: none;
    }

    .btn-back-icon:hover {
      color: white;
    }

    .ulasan-box {
      background-color: #1f1f1f;
      border-radius: 10px;
      padding: 20px;
      margin-top: 40px;
    }

    .ulasan-item {
      border-bottom: 1px solid #333;
      padding: 10px 0;
    }

    .ulasan-item:last-child {
      border-bottom: none;
    }

    .comment-form textarea {
      width: 100%;
      height: 100px;
      background: #2c2c2c;
      color: #fff;
      border: none;
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 10px;
    }

    .btn-comment {
      background-color: #ea001b;
      color: white;
      border: none;
      padding: 8px 20px;
      border-radius: 5px;
    }

    .comment {
      margin-bottom: 15px;
      padding: 10px;
      background-color: #1e1e1e;
      border-radius: 5px;
    }

    .comment-user {
      font-weight: bold;
    }

    .comment-date {
      font-size: 0.85rem;
      color: #aaa;
    }
  </style>
</head>

<body>
  <!-- Tombol Kembali -->
  <a href="home.php" class="btn-back-icon">&lt;</a>

  <div class="container mt-5">
    <div class="video-container text-center">
      <h1 class="video-title"><?= htmlspecialchars($video['title']) ?></h1>
      <video controls height="560px" width="100%" class="my-3">
        <source src="upload/<?= htmlspecialchars($video['filename']) ?>" type="video/mp4">
        Your browser does not support the video tag.
      </video>
    </div>

    <div class="ulasan-box">
      <h3>Komentar</h3>

      <?php if (isset($_SESSION['user_id'])): ?>
        <form method="post" action="comment.php" class="comment-form">
          <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
          <textarea name="content" placeholder="Tulis komentar Anda..." required></textarea>
          <button type="submit" class="btn btn-comment">Kirim Komentar</button>
        </form>
      <?php else: ?>
        <p class="login-prompt"><a href="login.php" class="text-warning">Login</a> untuk menambahkan komentar.</p>
      <?php endif; ?>

      <div class="comments-list mt-4">
        <?php foreach ($comments as $comment): ?>
          <div class="comment">
            <div class="comment-user"><?= htmlspecialchars($comment['username']) ?></div>
            <div class="comment-date"><?= $comment['created_at'] ?></div>
            <div class="comment-content"><?= htmlspecialchars($comment['content']) ?></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>

</body>
</html>
