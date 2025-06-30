<?php
require 'db.php';

// Get all categories for dropdown
$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];

    // Process file uploads
    $videoFile = $_FILES['video']['name'];
    $thumbFile = $_FILES['thumbnail']['name'];

    $videoPath = 'upload/' . basename($videoFile);
    $thumbPath = 'upload/' . basename($thumbFile);

    // Validate file types
    $videoExt = strtolower(pathinfo($videoFile, PATHINFO_EXTENSION));
    $thumbExt = strtolower(pathinfo($thumbFile, PATHINFO_EXTENSION));

    if (!in_array($videoExt, ['mp4', 'webm', 'mov'])) {
        $error = "Format video tidak didukung. Gunakan MP4, WebM, atau MOV.";
    } elseif (!in_array($thumbExt, ['jpg', 'jpeg', 'png', 'gif'])) {
        $error = "Format thumbnail tidak didukung. Gunakan JPG, PNG, atau GIF.";
    } elseif (
        move_uploaded_file($_FILES['video']['tmp_name'], $videoPath) &&
        move_uploaded_file($_FILES['thumbnail']['tmp_name'], $thumbPath)
    ) {

        $stmt = $pdo->prepare("INSERT INTO videos (title, filename, thumbnail, category_id) VALUES (?, ?, ?, ?)");
        $stmt->execute([$title, $videoFile, $thumbFile, $category_id]);

        $success = "Video berhasil diupload!";
    } else {
        $error = "Gagal upload file. Pastikan ukuran file tidak terlalu besar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>CANflix - Upload Video</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Bebas Neue', sans-serif;
            background-color: #141414;
            color: white;
            padding-top: 60px;
        }

        .upload-container {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 40px;
            border-radius: 4px;
            max-width: 800px;
            margin: 0 auto;
        }

        .upload-title {
            color: #fff;
            font-size: 2rem;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-control {
            background: #333;
            border: none;
            color: white;
            height: 50px;
            margin-bottom: 20px;
        }

        .form-control:focus {
            background: #454545;
            color: white;
            box-shadow: none;
        }

        .form-label {
            color: #8c8c8c;
            font-size: 1rem;
            margin-bottom: 8px;
            display: block;
        }

        .form-select {
            background: #333;
            border: none;
            color: white;
            height: 50px;
            margin-bottom: 20px;
        }

        .form-select:focus {
            background: #454545;
            color: white;
            box-shadow: none;
        }

        .btn-upload {
            background-color: #e50914;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 1.1rem;
            border-radius: 4px;
            width: 100%;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .btn-upload:hover {
            background-color: #f40612;
        }

        .error-message {
            color: #e50914;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .success-message {
            color: #5cb85c;
            margin-bottom: 20px;
            text-align: center;
            font-size: 1rem;
        }

        .file-hint {
            font-size: 0.8rem;
            color: #8c8c8c;
            margin-top: -15px;
            margin-bottom: 15px;
        }

        .navbar {
            background-color: #000 !important;
            margin-bottom: 40px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="upload-container">
            <h1 class="upload-title">Upload Video Baru</h1>

            <?php if ($error): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message"><?= $success ?></div>
                <div class="text-center mt-4">
                    <a href="index.php" class="btn btn-upload">Lihat Video</a>
                </div>
            <?php else: ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Judul Video</label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul video" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Kategori</label>
                        <select name="category_id" class="form-select" required>
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>"><?= $cat['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thumbnail</label>
                        <input type="file" name="thumbnail" accept="image/*" class="form-control" required>
                        <div class="file-hint">Format: JPG, PNG (Maksimal 5MB)</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Video</label>
                        <input type="file" name="video" accept="video/mp4,video/webm,video/mov" class="form-control" required>
                        <div class="file-hint">Format: MP4, WebM, MOV (Maksimal 100MB)</div>
                    </div>

                    <button type="submit" class="btn btn-upload">Upload Video</button>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>