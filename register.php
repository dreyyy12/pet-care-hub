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

    $nama_lengkap = $conn->real_escape_string($_POST['nama_lengkap']);
    $no_telp      = $conn->real_escape_string($_POST['no_telp']);
    $email        = $conn->real_escape_string($_POST['email']);
    $password     = $_POST['password']; 

    $check_email = $conn->query("SELECT * FROM users WHERE email = '$email'");
    
    if ($check_email && $check_email->num_rows > 0) {
        $error_msg = "Email ini sudah terdaftar! Silakan gunakan email lain.";
    } else {
        $sql = "INSERT INTO users (nama_lengkap, no_telp, email, password) 
                VALUES ('$nama_lengkap', '$no_telp', '$email', '$password')";
        
        if ($conn->query($sql) === TRUE) {
            $success_msg = "Akun berhasil dibuat! Anda akan dialihkan ke halaman login dalam 3 detik...";
            
            header("Location: login.php");
            exit();
        } else {
            $error_msg = "Gagal mendaftar: " . $conn->error;
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Daftar Akun Baru</title>
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

    .alert-success {
        background-color: #155724;
        color: #d4edda;
        border: 1px solid #c3e6cb;
        padding: 12px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-size: 14px;
        text-align: center;
    }
    </style>
</head>

<body>

    <div class="container">
        <nav class="nav-sim">
            <div class="logo">PetCareHub</div>
            <ul class="menu">
                <li>HOME</li>
                <li>ABOUT US</li>
                <li>SERVICES</li>
                <li>PRODUCT</li>
                <li>CONTACT</li>
            </ul>
            <div class="btn-gs">GET STARTED</div>
        </nav>

        <main class="auth-container">
            <div class="register-box">
                <h2>DAFTAR AKUN BARU</h2>
                <p class="subtitle">Akses penuh layanan & servis terbaik kami.</p>

                <?php if (isset($error_msg)): ?>
                <div class="alert-error"><?php echo $error_msg; ?></div>
                <?php endif; ?>

                <?php if (isset($success_msg)): ?>
                <div class="alert-success"><?php echo $success_msg; ?></div>
                <?php endif; ?>

                <form action="register.php" method="POST">
                    <div class="input-group">
                        <input type="text" name="nama_lengkap" placeholder="Nama Lengkap" required>
                    </div>
                    <div class="input-group">
                        <input type="tel" name="no_telp" placeholder="No Telepon" required>
                    </div>
                    <div class="input-group">
                        <input type="email" name="email" placeholder="Email" required>
                    </div>
                    <div class="input-group">
                        <input type="password" name="password" placeholder="Password" required>
                    </div>

                    <button type="submit" class="btn-submit">DAFTAR SEKARANG</button>
                </form>

                <p class="login-redirect">Sudah punya akun? <a href="login.php">Masuk di sini</a></p>
            </div>
        </main>
    </div>

</body>

</html>