
<?php
require 'db.php';

$id = $_GET['id'] ?? 0;

// Ambil data video berdasarkan ID
$stmt = $pdo->prepare("SELECT v.*, c.name AS category FROM videos v JOIN categories c ON v.category_id = c.id WHERE v.id = ?");
$stmt->execute([$id]);
$video = $stmt->fetch();

if (!$video) {
    echo "Konten tidak ditemukan.";
    exit;
}
// Simpan ulasan jika ada POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $review = trim($_POST['review'] ?? '');
    if ($review !== '') {
        $stmt = $pdo->prepare("INSERT INTO reviews (video_id, content) VALUES (?, ?)");
        $stmt->execute([$id, $review]);
    }
}

// Ambil semua ulasan untuk video ini
$ulasanStmt = $pdo->prepare("SELECT * FROM reviews WHERE video_id = ? ORDER BY created_at DESC");
$ulasanStmt->execute([$id]);
$ulasanList = $ulasanStmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Preview: <?= htmlspecialchars($video['title']) ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #121212;
      color: white;
    }
    .poster {
      max-width: 100%;
      border-radius: 15px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    }
    .btn-play {
      font-size: 1.2rem;
      background-color: #e50914;
      color: white;
    }
    .btn-play:hover {
      background-color: #ff0f1b;
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
  </style>
</head>
<body>
  <!-- Tombol Kembali -->
  <a href="home.php" class="btn-back-icon">←</a>

  <div class="container py-5">
    <div class="row align-items-center">
      <div class="col-md-4 text-center mb-4 mb-md-0">
        <img src="upload/<?= htmlspecialchars($video['thumbnail']) ?>" alt="<?= htmlspecialchars($video['title']) ?>"class="img-fluid"/>
      </div>
      <div class="col-md-8">
        <h1 class="mb-3"><?= htmlspecialchars($video['title']) ?></h1>
        <p class="mb-2"><strong>Kategori:</strong> <?= htmlspecialchars($video['category']) ?></p>
        <p class="mb-4">
          <?= htmlspecialchars($video['description'] ?? 'Film ini merupakan bagian dari koleksi kami.') ?>
        </p>
        <div class="d-flex gap-3 mb-4">
          <a href="pilem.php?id=<?= $video['id'] ?>" class="btn btn-play">▶️ Play Film</a>
        </div>
      </div>
    </div>

    <!-- Ulasan -->
<div class="ulasan-box">
  <h4 class="mb-3">Ulasan Penonton</h4>

  <form method="POST">
    <div class="mb-3">
      <label for="inputUlasan" class="form-label">Tulis Ulasan Anda</label>
      <textarea
        class="form-control"
        id="inputUlasan"
        name="review"
        rows="3"
        placeholder="Ketik ulasan di sini..."
        required
      ></textarea>
    </div>
    <button type="submit" class="btn btn-play">Kirim Ulasan</button>
  </form>

  <div id="daftar-ulasan" class="mt-4">
    <?php if (count($ulasanList) > 0): ?>
      <?php foreach ($ulasanList as $ulasan): ?>
        <div class="ulasan-item"><?= htmlspecialchars($ulasan['content']) ?></div>
      <?php endforeach; ?>
    <?php else: ?>
      <div class="text-muted">Belum ada ulasan.</div>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
