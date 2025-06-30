<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: index.php');
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <title>CANflix - Login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Bebas Neue', sans-serif;
            background-color: #141414;
            color: white;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 60px 68px;
            border-radius: 4px;
            width: 100%;
            max-width: 450px;
        }

        .login-title {
            color: #fff;
            font-size: 2rem;
            margin-bottom: 28px;
        }

        .form-control {
            background: #333;
            border: none;
            color: white;
            height: 50px;
            margin-bottom: 16px;
        }

        .form-control:focus {
            background: #454545;
            color: white;
            box-shadow: none;
        }

        .btn-login {
            background-color: #e50914;
            color: white;
            border: none;
            padding: 12px;
            font-size: 1rem;
            border-radius: 4px;
            width: 100%;
            margin-top: 24px;
            margin-bottom: 12px;
        }

        .btn-login:hover {
            background-color: #f40612;
        }

        .form-text {
            color: #737373;
        }

        .text-signup {
            color: #737373;
            margin-top: 16px;
        }

        .text-signup a {
            color: white;
            text-decoration: none;
        }

        .text-signup a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e50914;
            margin-bottom: 16px;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="login-container">
        <h1 class="login-title">Login</h1>

        <?php if (isset($error)): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <form method="post" action="login.php">
            <div class="mb-3">
                <input type="text" class="form-control" name="username" placeholder="Username" required>
            </div>
            <div class="mb-3">
                <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <button type="submit" class="btn btn-login">Login</button>
        </form>

        <div class="form-text">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="rememberMe">
                <label class="form-check-label" for="rememberMe">
                    Ingat saya
                </label>
            </div>
        </div>

        <div class="text-signup">
            Belum punya akun? <a href="register.php">Daftar sekarang</a>.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>