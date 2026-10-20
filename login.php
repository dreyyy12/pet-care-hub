<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db   = "softdev";

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Koneksi Gagal: " . $conn->connect_error);
    }

    $email = $conn->real_escape_string($_POST['email']);
    $telp = $conn->real_escape_string($_POST['telp']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = '$email' AND no_telp = '$telp' AND password = '$password'";
    $result = $conn->query($sql);

    if ($result && $result->num_rows > 0) {
        $user_data = $result->fetch_assoc();
        
        $_SESSION['username'] = $user_data['nama_lengkap']; 
        $_SESSION['no_nota'] = time() . "_" . rand(1000, 9999);

        $conn->close();
        
        // ========================================================
        // BAGIAN YANG DIUBAH: Logika dinamis untuk redirect halaman
        // ========================================================
        if (isset($_GET['redirect'])) {
            $ke_halaman = $_GET['redirect']; // Mengambil string 'service.php' atau 'produk.php'
        } else {
            $ke_halaman = "home.php"; // Default jika user ke login.php secara manual
        }

        header("Location: " . $ke_halaman);
        exit();
        // ========================================================
        
    } else {
        $error_msg = "Data Akun tidak ditemukan atau Password salah!";
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Masuk Akun</title>
    <link rel="stylesheet" type="text/css" href="login.css">
    <style>
    .alert-error {
        background-color: #721c24;
        color: #f8d7da;
        border: 1px solid #f5c6cb;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        text-align: center;
    }

    .login-redirect {
        position: relative;
        z-index: 10;
        margin-top: 20px;
        color: #888888;
        font-size: 14px;
    }

    .login-redirect a {
        color: #e5c38c;
        text-decoration: none;
        font-weight: bold;
        position: relative;
        z-index: 15;
        cursor: pointer;
        transition: color 0.2s ease;
    }

    .login-redirect a:hover {
        color: #d4b27b;
        text-decoration: underline;
    }
    </style>
</head>

<body>

    <main class="auth-container">
        <div class="register-box">
            <h2>MASUK AKUN</h2>
            <p class="subtitle">Silakan masuk untuk mengakses layanan & produk kami.</p>

            <?php if (isset($error_msg)): ?>
            <div class="alert-error"><?php echo $error_msg; ?></div>
            <?php endif; ?>

            <form action="" method="POST">
                <div class="input-group">
                    <input type="email" name="email" placeholder="Email" required>
                </div>
                <div class="input-group">
                    <input type="tel" name="telp" placeholder="No. Telepon" required>
                </div>
                <div class="input-group">
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <button type="submit" class="btn-submit">MASUK SEKARANG</button>
            </form>

            <p class="login-redirect">Belum punya akun? <a href="register.php">Daftar di sini</a></p>
        </div>
    </main>

</body>

</html>