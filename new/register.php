<?php
// register.php
session_start();
require 'db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Validate password match
    if ($password !== $confirm_password) {
        $error = "Password tidak cocok!";
    } else {
        // Check if username exists
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);

        if ($stmt->rowCount() > 0) {
            $error = "Username sudah digunakan!";
        } else {
            // Hash password and register user
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
    <title>CANflix - Daftar</title>
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

        .register-container {
            background-color: rgba(0, 0, 0, 0.75);
            padding: 60px 68px;
            border-radius: 4px;
            width: 100%;
            max-width: 450px;
        }

        .register-title {
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

        .btn-register {
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

        .btn-register:hover {
            background-color: #f40612;
        }

        .form-text {
            color: #737373;
        }

        .text-login {
            color: #737373;
            margin-top: 16px;
        }

        .text-login a {
            color: white;
            text-decoration: none;
        }

        .text-login a:hover {
            text-decoration: underline;
        }

        .error-message {
            color: #e50914;
            margin-bottom: 16px;
            text-align: center;
        }

        .success-message {
            color: #5cb85c;
            margin-bottom: 16px;
            text-align: center;
        }

        .password-hint {
            font-size: 0.8rem;
            color: #8c8c8c;
            margin-top: -10px;
            margin-bottom: 15px;
        }
    </style>
</head>

<body>
    <div class="register-container">
        <h1 class="register-title">Daftar</h1>

        <?php if ($error): ?>
            <div class="error-message"><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="success-message"><?= $success ?></div>
        <?php else: ?>
            <form method="post" action="register.php">
                <div class="mb-3">
                    <input type="text" class="form-control" name="username" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                    <div class="password-hint">Password minimal 6 karakter</div>
                </div>
                <div class="mb-3">
                    <input type="password" class="form-control" name="confirm_password" placeholder="Konfirmasi Password" required>
                </div>
                <button type="submit" class="btn btn-register">Daftar</button>
            </form>
        <?php endif; ?>

        <div class="text-login">
            Sudah punya akun? <a href="login.php">Login sekarang</a>.
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>