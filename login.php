<?php
session_start();
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    //CEK USER
    $stmt = $pprepare('SELECT * FROM data_pengguna WHERE email = :email');
    $stmt->execudo->te(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // CEK USER DAN PASSWORDNYA
    if (password_verify($password, $user['password'])) {
        $_SESSION['loggedin'] = true;

        // verif password
        
        header('Location: dashboard.php');
        exit();
    } else {
        $error = 'Email atau password salah!';
    }
}
?>


<!-- Desain CSS -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="css/LogRes.css" rel="stylesheet"> 
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
        }
        .login-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0 1rem rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .error {
            color: red;
        }
        h2 {
            font-weight: 600;
        }
        label {
            font-weight: 400;
        }
        .btn {
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Login</h2>
        <form method="post" action="">
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="text" id="email" name="email" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="password">Password :</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Login</button>
            <!-- Menampilkan pesan error jika ada -->
            <?php if (isset($error)) { echo "<p class='error text-center'>$error</p>"; } ?>
        </form>
        <div class="text-center mt-3">
        <a href="register.php" class="d-block">Don't have an account? Register</a>
        <a href="forgot.php" class="d-block">Forgot Password?</a>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
