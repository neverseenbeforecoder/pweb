<?php
// index.php
require 'db.php';
$stmt = $pdo->query("SELECT v.*, c.name AS category FROM videos v JOIN categories c ON v.category_id = c.id ORDER BY v.id DESC LIMIT 6");
$videos = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>CANflix</title>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

  <style>
    body {
      font-family: 'Bebas Neue', sans-serif;
      background-color: #141414;
      color: white;
      text-align: center;
    }

    .navbar {
      background-color: #000 !important;
    }

    .thumbnail {
      width: 100%;
      height: 150px;
      object-fit: cover;
      border-radius: 4px;
    }

    .section-title {
      font-size: 1.5rem;
      margin: 20px 0 10px;
      text-align: left;
      padding-left: 15px;
    }

    .card-container {
      display: flex;
      overflow-x: auto;
      gap: 15px;
      padding: 0 15px;
      margin-bottom: 30px;
    }

    .card-container::-webkit-scrollbar {
      height: 8px;
    }

    .card-container::-webkit-scrollbar-thumb {
      background: #555;
      border-radius: 10px;
    }

    .video-card {
      flex: 0 0 auto;
      width: 200px;
      transition: transform 0.3s;
    }

    .video-card:hover {
      transform: scale(1.05);
    }

    .card {
      background-color: #222;
      border: none;
      color: white;
    }

    .card-title {
      font-size: 1rem;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    .card-text {
      font-size: 0.8rem;
      color: #aaa;
    }

    .btn-primary {
      background-color: #e50914;
      border: none;
      font-size: 0.8rem;
    }

    .btn-primary:hover {
      background-color: #f40612;
    }

    .navbar-brand {
      color: #e50914 !important;
      font-size: 1.8rem !important;
    }

    .nav-link {
      color: white !important;
      font-size: 1.1rem !important;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand px-4">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">CANflix</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="register.php">DAFTAR</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="login.php">LOGIN</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="upload.php">UPLOAD PIDIO</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container-fluid mt-4">
    <div class="section-title">Video Terbaru</div>
    <div class="card-container">
      <?php foreach ($videos as $video): ?>
        <div class="video-card">
          <div class="card">
            <img src="upload/<?= $video['thumbnail'] ?>" class="card-img-top thumbnail">
            <div class="card-body">
              <h5 class="card-title"><?= $video['title'] ?></h5>
              <p class="card-text">Kategori: <?= $video['category'] ?></p>
              <a href="video.php?id=<?= $video['id'] ?>" class="btn btn-primary">Tonton</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Additional sections can be added here -->
    <div class="section-title">Populer</div>
    <div class="card-container">
      <?php foreach ($videos as $video): ?>
        <div class="video-card">
          <div class="card">
            <img src="upload/<?= $video['thumbnail'] ?>" class="card-img-top thumbnail">
            <div class="card-body">
              <h5 class="card-title"><?= $video['title'] ?></h5>
              <a href="video.php?id=<?= $video['id'] ?>" class="btn btn-primary">Tonton</a>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>