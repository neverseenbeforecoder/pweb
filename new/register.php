<?php
// register.php
session_start();
require 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($password !== $confirm_password) {
        $error = "Password tidak cocok!";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $error = "Username sudah digunakan!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

            if ($stmt->execute([$username, $hashed_password])) {
                $success = "Registrasi berhasil! Silakan <a href='login.php' class='text-white'>login</a>.";
            } else {
                $error = "Gagal registrasi. Silakan coba lagi.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Registrasi - RAHMAT CINEMA</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body, html {
      height: 100%;
      margin: 0;
    }
    body {
      background: url('https://img.freepik.com/free-photo/movie-background-collage_23-2149876028.jpg?semt=ais_hybrid&w=740') no-repeat center center fixed;
      background-size: cover;
    }
    .login-container {
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
    }
    .login-box {
      max-width: 400px;
      width: 100%;
      background: rgba(0, 0, 0, 0.7);
      color: #fff;
      padding: 2rem;
      border-radius: 0.75rem;
    }
  </style>
</head>
<body>

  <nav class="navbar navbar-expand-lg navbar-dark px-4">
    <a class="navbar-brand fw-bold fs-3" href="#">RAHMAT CINEMA</a>
  </nav>

  <div class="container login-container">
    <div class="login-box">
      <h3 class="text-center mb-4">Registrasi</h3>

      <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <?php if ($success): ?>
        <div class="alert alert-success"><?= $success ?></div>
      <?php else: ?>
      <form method="POST" action="">
        <div class="mb-3">
          <label for="username" class="form-label">Email/Username</label>
          <input type="text" class="form-control" id="username" name="username" required placeholder="you@example.com" />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required placeholder="Your password" />
        </div>
        <div class="mb-3">
          <label for="confirm_password" class="form-label">Konfirmasi Password</label>
          <input type="password" class="form-control" id="confirm_password" name="confirm_password" required placeholder="Ketik ulang password" />
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary">Sign Up</button>
        </div>
        <div class="text-center mt-3">
          <span>Sudah punya akun?</span> <a href="login.php" class="text-decoration-none text-light">Sign In</a>
        </div>
      </form>
      <?php endif; ?>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
