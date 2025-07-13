<?php
require 'db.php';

$categories = $pdo->query("SELECT * FROM categories")->fetchAll();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category_id = $_POST['category_id'];
    $description = $_POST['description'];


    $videoFile = $_FILES['video']['name'];
    $thumbFile = $_FILES['thumbnail']['name'];

    $videoPath = 'upload/' . basename($videoFile);
    $thumbPath = 'upload/' . basename($thumbFile);

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
        $stmt = $pdo->prepare("INSERT INTO videos (title, filename, thumbnail, category_id, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$title, $videoFile, $thumbFile, $category_id, $description]);

        $success = "Video berhasil diupload!";
    } else {
        $error = "Gagal upload file. Pastikan ukuran file tidak terlalu besar.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Upload Video - RAHMAT CINEMA</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
        }

        body {
            background: url('https://img.freepik.com/free-photo/movie-background-collage_23-2149876028.jpg?semt=ais_hybrid&w=740') no-repeat center center fixed;
            background-size: cover;
            color: #fff;
        }

        .upload-container {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .upload-box {
            max-width: 500px;
            width: 100%;
            background: rgba(0, 0, 0, 0.75);
            padding: 2rem;
            border-radius: 0.75rem;
        }

        .form-label {
            color: #ccc;
        }

        .form-control,
        .form-select {
            background-color: #333;
            color: white;
            border: none;
        }

        .form-control:focus,
        .form-select:focus {
            background-color: #444;
            color: white;
        }

        .btn-upload {
            background-color: rgba(0, 0, 0, 0.7);
            color: white;
            border: none;
            padding: 0.75rem;
        }

        .btn-upload:hover {
            background-color: #f40612;
        }

        .error-message {
            color: #ff6b6b;
            text-align: center;
            margin-bottom: 1rem;
        }

        .success-message {
            color: #4caf50;
            text-align: center;
            margin-bottom: 1rem;
        }

        .form-check-label {
            margin-right: 15px;
        }
    </style>
</head>

<body>

    <div class="container upload-container">
        <div class="upload-box">
            <h3 class="text-center mb-4">Upload Video</h3>

            <?php if ($error): ?>
                <div class="error-message"><?= $error ?></div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="success-message"><?= $success ?></div>
                <div class="d-grid mt-3">
                    <a href="home.php" class="btn btn-upload">Lihat Semua Video</a>
                </div>
            <?php else: ?>
                <form method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Judul Video</label>
                        <input type="text" name="title" class="form-control" placeholder="Masukkan judul video" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Tuliskan deskripsi video" required></textarea>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Kategori</label><br>
                        <?php foreach ($categories as $cat): ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="category_id" id="cat<?= $cat['id'] ?>" value="<?= $cat['id'] ?>" required>
                                <label class="form-check-label" for="cat<?= $cat['id'] ?>"><?= $cat['name'] ?></label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Thumbnail</label>
                        <input type="file" name="thumbnail" accept="image/*" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">File Video</label>
                        <input type="file" name="video" accept="video/mp4,video/webm,video/mov" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-upload">Upload</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>