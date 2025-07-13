<?php
require 'db.php';
$stmt = $pdo->query("SELECT v.*, c.name AS category FROM videos v JOIN categories c ON v.category_id = c.id ORDER BY v.id DESC LIMIT 8");
$videos = $stmt->fetchAll();
$carouselVideos = array_slice($videos, 0, 3); // Ambil 3 video pertama untuk carousel
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF‑8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>RAHMAT CINEMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar {
            background-color: rgba(0, 0, 0, 0.3) !important;
            backdrop-filter: blur(10px);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
            /* Lebih tinggi agar di depan carousel */
            height: 56px;
        }

        body {
            background: #000;
            color: #fff;
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            padding-top: 56px;
        }

        .hero-carousel,
        .hero-item {
            position: relative;
            width: 75%;
            padding-top: 40%;
        }

        .hero-item img {
            object-fit: cover;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
        }

        .carousel-inner {
            position: relative;
            /* Fix untuk layering */
            z-index: 1;
        }

        .carousel-item img {
            position: relative;
            /* Fix untuk layering */
            z-index: 1;
        }

        .hero-caption {
            position: absolute;
            bottom: 20%;
            left: 5%;
            max-width: 60%;
            z-index: 2;
            /* Supaya teks di atas gambar */
        }

        .hero-caption h2 {
            font-size: 2.5rem;
            margin-bottom: .5rem;
        }

        .hero-caption p {
            font-size: 1.1rem;
            margin-bottom: 1rem;
        }

        .hero-caption .btn {
            background: #ea001b;
            border: none;
            padding: .75rem 1.5rem;
        }

        .content-section {
            padding: 2rem 5%;
        }

        .section-title {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        .card-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
            border-radius: .5rem;
        }

        .video-card {
            position: relative;
            border-radius: .5rem;
            overflow: hidden;
        }

        .video-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .video-card .title {
            position: absolute;
            bottom: 1rem;
            left: 1rem;
            font-weight: bold;
        }

        .carousel-control-prev,
        .carousel-control-next {
            top: 50%;
            transform: translateY(-50%);
            width: 5%;
        }

        .carousel-control-prev-icon,
        .carousel-control-next-icon {
            width: 2rem;
            height: 2rem;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark px-4">
        <a class="navbar-brand fw-bold fs-3" href="#">RAHMAT CINEMA</a>
        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="login.php">Log out</a>
                </li>
            </ul>

        </div>
    </nav>

    <!-- Carousel Dinamis -->
    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-inner">
            <?php foreach ($carouselVideos as $index => $video): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                   <img src="upload/<?= htmlspecialchars($video['thumbnail']) ?>" alt="<?= htmlspecialchars($video['title']) ?>" class="img-fluid" />
                    <div class="hero-caption text-white">
                        <h2><?= htmlspecialchars($video['title']) ?></h2>
                        <p><?= htmlspecialchars($video['category']) ?></p>
                        <a href="preview.php?id=<?= $video['id'] ?>">
                            <button class="btn btn-lg">Watch Now</button>
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>

    <!-- Video Sections -->
    <div class="content-section">
        <h3 class="section-title">Trending Now</h3>
        <div class="row g-3">
            <div class="row g-3">
                <?php foreach ($videos as $video): ?>
                    <div class="col-6 col-sm-4 col-md-3">
                        <a href="pilem.php?id=<?= $video['id'] ?>" style="text-decoration: none; color: inherit;">
                            <div class="video-card">
                                <img src="<?= htmlspecialchars($video['thumbnail']) ?>" alt="">
                                <div class="card-overlay"></div>
                                <div class="title"><?= htmlspecialchars($video['title']) ?></div>
                            </div>
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>



            <!-- Footer Bootstrap -->
            <footer class="bg-black text-light py-4 mt-5">
                <div class="container">
                    <div class="row text-center text-md-start">
                        <div class="col-md-4 mb-3 mb-md-0">
                            <h5 class="fw-bold">RAHMAT CINEMA</h5>
                            <p class="small mb-0">Your ultimate source for movies & TV shows.</p>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <h6 class="fw-bold">Navigation</h6>
                            <ul class="list-unstyled">
                                <li class="nav-item">
                                    <a class="nav-link" href="home.php">Home</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="login.php">Log out</a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <h6 class="fw-bold">Contact</h6>
                            <p class="small mb-1">Email: info@rahmatcinema.com</p>
                            <p class="small mb-0">Phone: +62 812 3456 7890</p>
                        </div>
                    </div>
                    <hr class="border-light my-3">
                    <div class="text-center small">&copy; 2025 Rahmat Cinema. All rights reserved.</div>
                </div>
            </footer>


            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>