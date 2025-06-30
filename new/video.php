<?php
// video.php
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
    <title>CANflix - <?= $video['title'] ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Bebas Neue', sans-serif;
            background-color: #141414;
            color: white;
        }

        .video-container {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 40px;
            border-radius: 4px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .video-title {
            color: #fff;
            font-size: 2.5rem;
            margin-bottom: 20px;
        }

        video {
            width: 100%;
            height: auto;
            background-color: #000;
            border-radius: 4px;
        }

        .comments-section {
            margin-top: 40px;
            padding: 20px;
            background-color: rgba(0, 0, 0, 0.5);
            border-radius: 4px;
        }

        .comment-title {
            font-size: 1.8rem;
            margin-bottom: 20px;
            color: #fff;
        }

        .comment-form textarea {
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 15px;
            width: 100%;
            margin-bottom: 15px;
        }

        .comment-form textarea:focus {
            background-color: #454545;
            color: white;
            box-shadow: none;
        }

        .btn-comment {
            background-color: #e50914;
            color: white;
            border: none;
            padding: 10px 25px;
            font-size: 1.1rem;
            border-radius: 4px;
            transition: background-color 0.3s;
        }

        .btn-comment:hover {
            background-color: #f40612;
        }

        .comment {
            background-color: rgba(255, 255, 255, 0.1);
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }

        .comment-user {
            color: #e50914;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }

        .comment-date {
            color: #8c8c8c;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .comment-content {
            font-size: 1.1rem;
            line-height: 1.4;
        }

        .login-prompt {
            color: #8c8c8c;
            font-size: 1.1rem;
        }

        .login-prompt a {
            color: #e50914;
            text-decoration: none;
        }

        .login-prompt a:hover {
            text-decoration: underline;
        }

        .navbar {
            background-color: #000 !important;
            margin-bottom: 40px;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg px-4">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php" style="color: #e50914 !important; font-size: 1.8rem !important;">CANflix</a>
            <div class="navbar-nav">
                <a class="nav-link" href="index.php" style="color: white !important; font-size: 1.1rem !important;">Kembali ke Beranda</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="video-container">
            <h1 class="video-title"><?= $video['title'] ?></h1>
            <video controls>
                <source src="upload/<?= $video['filename'] ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>

            <div class="comments-section">
                <h3 class="comment-title">Komentar</h3>

                <?php session_start();
                if (isset($_SESSION['user_id'])): ?>
                    <form method="post" action="comment.php" class="comment-form">
                        <input type="hidden" name="video_id" value="<?= $video['id'] ?>">
                        <textarea name="content" placeholder="Tulis komentar Anda..." required></textarea>
                        <button type="submit" class="btn btn-comment">Kirim Komentar</button>
                    </form>
                <?php else: ?>
                    <p class="login-prompt"><a href="login.php">Login</a> untuk menambahkan komentar.</p>
                <?php endif; ?>

                <div class="comments-list">
                    <?php foreach ($comments as $comment): ?>
                        <div class="comment">
                            <div class="comment-user"><?= $comment['username'] ?></div>
                            <div class="comment-date"><?= $comment['created_at'] ?></div>
                            <div class="comment-content"><?= htmlspecialchars($comment['content']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>