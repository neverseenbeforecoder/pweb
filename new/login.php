<?php
session_start();
require 'db.php';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: home.php');
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - RAHMAT CINEMA</title>
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
      <h3 class="text-center mb-4">Login</h3>

      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ="masukkin username sama password dulu lah" ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="mb-3">
          <label for="username" class="form-label">Email / Username</label>
          <input type="text" class="form-control" id="username" name="username" required placeholder="you@example.com" />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label">Password</label>
          <input type="password" class="form-control" id="password" name="password" required placeholder="Your password" />
        </div>
        <div class="d-grid">
          <button type="submit" class="btn btn-primary" href="home.php">Log In</button>
        </div>
        <div class="text-center mt-3">
          <span>Belum punya akun?</span> <a href="register.php" class="text-decoration-none text-light">Sign Up</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>
