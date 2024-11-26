<?php
session_start();
require 'config.php';
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    $stmt = $pdo->prepare("SELECT * FROM data_pengguna WHERE email = :email");
    // $stmt->bind_param("s", $email);
    // $stmt->execute();
    $stmt->execute([':email' => $email]);
    $result = $stmt->fetchAll();

    if (count($result) > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['email'] = $email;
        $_SESSION['otp'] = $otp;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'extentionrays@gmail.com';
            $mail->Password = 'twfj myna tigb bkhz';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('extentionrays@gmail.com', 'ADEK');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Kode OTP Anda';
            $mail->Body = "<p>Kode OTP Anda adalah: <strong>$otp</strong></p>";

            $mail->send();
            header('Location: verif.php');
            exit;
        } catch (Exception $e) {
            $error = "Gagal mengirim email. Error: {$mail->ErrorInfo}";
        }
    } else {
        $error = "Email tidak ditemukan.";
    }
    // $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center">Lupa Password</h2>
        <?php if (isset($error)): ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="forgot.php" method="POST">
            <input type="email" name="email" placeholder="Email" required class="w-full p-3 mb-4 border border-gray-300 rounded">
            <button type="submit" class="w-full bg-blue-500 text-white p-3 rounded hover:bg-blue-600">Kirim OTP</button>
        </form>
    </div>
</body>
</html>