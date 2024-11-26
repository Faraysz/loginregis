<?php
session_start();
require 'config.php';

// Function to generate random 5-character id_user
function generateUserId() {
    return strtoupper(substr(md5(uniqid(rand(), true)), 0, 5));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = trim($_POST['nama_lengkap']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $no_hp = $_POST['no_hp'] ?? '';  // Optional
    $berat_badan = $_POST['berat_badan'] ?? '';  // Optional
    $tinggi_badan = $_POST['tinggi_badan'] ?? '';  // Optional

    // VALIDASI INPUTAN USER 
    if ($password !== $confirmPassword) {
        $error = 'Password dan konfirmasi password tidak cocok!';
    } else {
        // Cek apakah username atau email sudah terdaftar di DB
        $stmt = $pdo->prepare('SELECT * FROM data_pengguna WHERE nama_lengkap = :nama_lengkap OR email = :email');
        $stmt->execute(['nama_lengkap' => $nama_lengkap, 'email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $error = 'Username atau email sudah terdaftar!';
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Generate new id_user
            $id_user = generateUserId();

            // INSERT data pengguna baru
            $stmt = $pdo->prepare('INSERT INTO data_pengguna (id_user, nama_lengkap, password, email, no_hp, berat_badan, tinggi_badan) 
                                   VALUES (:id_user, :nama_lengkap, :password, :email, :no_hp, :berat_badan, :tinggi_badan)');
            $stmt->execute([
                'id_user' => $id_user,
                'nama_lengkap' => $nama_lengkap,
                'password' => $hashedPassword,
                'email' => $email,
                'no_hp' => $no_hp,
                'berat_badan' => $berat_badan,
                'tinggi_badan' => $tinggi_badan
            ]);

            // Redirect setelah berhasil registrasi
            header('Location: login.php');
            exit();
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | Your App Name</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .register-container {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 900px;
            display: flex;
        }
        .image-section {
            flex: 1;
            background: url('gambar/buahdansayur2.jpg') center/cover no-repeat;
            position: relative;
        }
        .form-section {
            flex: 1;
            padding: 40px;
        }
        .form-floating {
            margin-bottom: 1rem;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
            padding: 10px 0;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .error-message {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 5px;
        }
        h2 {
            font-weight: 600;
        }
        label {
            font-weight: 400;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="image-section"></div>
        <div class="form-section">
            <h2 class="text-center mb-4">Create Your Account !</h2>
            <?php
            if (!empty($errors)) {
                echo '<div class="alert alert-danger" role="alert">';
                foreach ($errors as $error) {
                    echo $error . '<br>';
                }
                echo '</div>';
            }
            ?>
            <form method="post" action="" novalidate>
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="nama_lengkap" required>
                    <label for="nama_lengkap">Full Name</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com" required>
                    <label for="email">Email</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <label for="password">Password</label>
                </div>
                <div class="form-floating mb-3">
                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                    <label for="confirm_password">Confirm Password</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>
                <div class="text-center">
                    <a href="login.php" class="text-decoration-none">Already have an account? Login</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Client-side validation
        document.querySelector('form').addEventListener('submit', function(e) {
            let isValid = true;
            const username = document.getElementById('nama_lengkap');
            const email = document.getElementById('email');
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm_password');

            // Reset previous error messages
            document.querySelectorAll('.error-message').forEach(el => el.remove());

            if (username.value.trim() === '') {
                addError(username, 'Full Name is required');
                isValid = false;
            }
            if (email.value.trim() === '') {
                addError(email, 'Email is required');
                isValid = false;
            }
            if (password.value === '') {
                addError(password, 'Password is required');
                isValid = false;
            }
            if (password.value !== confirmPassword.value) {
                addError(confirmPassword, 'Passwords do not match');
                isValid = false;
            }

            if (!isValid) {
                e.preventDefault();
            }
        });

        function addError(element, message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = message;
            element.parentNode.appendChild(errorDiv);
        }
    </script>
</body>
</html>
