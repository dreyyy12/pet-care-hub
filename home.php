<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Home</title>
    <style>
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        background-color: #000;
        color: #f5f5f3;
        padding: 40px 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        min-height: 100vh;
    }

    .container {
        max-width: 1600px;
        width: 90%;
        margin: 0 auto;
    }

    /* NAVBAR STYLES */
    .nav-sim {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 60px;
        border-bottom: 1px solid #141412;
        padding-bottom: 20px;
    }

    .logo {
        font-size: 20pt;
        color: #e5c185;
        font-weight: bold;
        font-family: serif;
    }

    .menu {
        display: flex;
        gap: 30px;
        list-style: none;
        font-size: 10pt;
        color: #a3a3a1;
        align-items: center;
    }

    .menu li a {
        color: #a3a3a1;
        text-decoration: none;
        letter-spacing: 1px;
        transition: color 0.2s ease;
        display: inline-block;
        padding-bottom: 5px;
    }

    .menu li a:hover {
        color: #fff;
        border-bottom: 2px solid #6d5b43;
    }

    .menu li.active a {
        color: #fff;
        font-weight: 500;
        border-bottom: 2px solid #e5c185;
    }

    /* KONSISTENSI STYLE TOMBOL NAVIGASI KANAN */
    .btn-logout {
        background: #e5c185;
        color: #000;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 10pt;
        cursor: pointer;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-logout:hover {
        background: #a71d2a;
        color: #ffffff;
    }

    .btn-gs {
        background: #e5c185;
        color: #000;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 10pt;
        cursor: pointer;
        letter-spacing: 0.5px;
        transition: background 0.2s ease;
        text-decoration: none;
        display: inline-block;
    }

    .btn-gs:hover {
        background: #d4b27b;
    }

    /* HERO SECTION STYLES */
    .hero-section {
        margin-top: 40px;
        max-width: 1000px;
        text-align: left;
    }

    .welcome-text {
        font-size: 11pt;
        text-transform: uppercase;
        color: #a3a3a1;
        letter-spacing: 3px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 30px;
    }

    .welcome-text::before {
        content: "";
        display: inline-block;
        width: 40px;
        height: 1px;
        background-color: #e5c185;
    }

    .hero-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 56pt;
        font-weight: 500;
        line-height: 1.15;
        color: #ffffff;
        margin-bottom: 35px;
        letter-spacing: -0.5px;
    }

    .hero-title em {
        font-style: italic;
        color: #e5c185;
        font-family: inherit;
    }

    .hero-description {
        font-size: 11pt;
        color: #8c8c8a;
        line-height: 1.7;
        max-width: 550px;
        margin-bottom: 45px;
        letter-spacing: 0.3px;
    }

    /* BUTTON GROUP STYLES */
    .btn-group {
        display: flex;
        gap: 20px;
    }

    .btn-primary {
        background-color: #e5c185;
        color: #000000;
        padding: 16px 32px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 10pt;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: none;
        cursor: pointer;
        transition: background 0.2s ease;
    }

    .btn-primary:hover {
        background-color: #d4b27b;
    }

    .btn-secondary {
        background-color: #161616;
        color: #8c8c8a;
        padding: 16px 32px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 10pt;
        text-transform: uppercase;
        letter-spacing: 1px;
        border: 1px solid #222220;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .btn-secondary:hover {
        background-color: #222220;
        color: #ffffff;
        border-color: #333330;
    }
    </style>
</head>

<body>

    <div class="container">
        <nav class="nav-sim">
            <div class="logo">PetCareHub</div>
            <ul class="menu">
                <li class="active"><a href="home.php">HOME</a></li>
                <li><a href="tentang.php">ABOUT US</a></li>
                <li><a href="service.php">SERVICES</a></li>
                <li><a href="produk.php">PRODUCT</a></li>
                <li><a href="kontak.php">CONTACT</a></li>
            </ul>
            
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?');">LOGOUT</a>
            <?php else: ?>
                <a href="login.php" class="btn-gs">GET STARTED</a>
            <?php endif; ?>
        </nav>

        <main class="hero-section">
            <div class="welcome-text">Welcome to PetCareHub</div>

            <h1 class="hero-title">
                Complete Care for Your <em>Beloved</em> Pets
            </h1>

            <p class="hero-description">
                Pet Care Hub hadir sebagai solusi lengkap untuk kebutuhan hewan peliharaan Anda -
                mulai dari grooming, layanan kesehatan, hingga perlengkapan berkualitas untuk
                menjaga kenyamanan dan kebahagiaan peliharaan tercinta.
            </p>

            <div class="btn-group">
                <button class="btn-primary" onclick="window.location.href='service.php'">Lihat Layanan</button>
                <button class="btn-secondary" onclick="window.location.href='tentang.php'">Tentang Kami</button>
            </div>
        </main>
    </div>

</body>
</html>