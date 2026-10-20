<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Contact Us</title>
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

    /* CONTACT SECTION STYLES */
    .contact-section {
        margin-top: 40px;
        width: 100%;
    }

    .sub-title {
        font-size: 11pt;
        text-transform: uppercase;
        color: #a3a3a1;
        letter-spacing: 3px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .sub-title::before, .sub-title::after {
        content: "";
        display: inline-block;
        width: 40px;
        height: 1px;
        background-color: #e5c185;
    }

    .main-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 36pt;
        font-weight: 500;
        line-height: 1.2;
        color: #ffffff;
        margin-bottom: 50px;
    }

    .main-title em {
        font-style: italic;
        color: #e5c185;
        font-family: inherit;
    }

    /* GRID LAYOUT */
    .contact-grid {
        display: grid;
        grid-template-columns: 1.1fr 0.9fr;
        gap: 60px;
        align-items: flex-start;
    }

    .contact-block {
        display: flex;
        flex-direction: column;
        gap: 15px;
        margin-bottom: 40px;
    }

    .info-header {
        display: flex;
        align-items: center;
        gap: 15px;
    }

    .info-icon {
        color: #e5c185;
        font-size: 24pt;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .info-label {
        font-size: 14pt;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .info-text {
        font-size: 12pt;
        color: #a3a3a1;
        line-height: 1.7;
        padding-left: 2px;
    }

    .info-text-highlight {
        color: #e5c185;
        font-weight: 500;
    }

    /* IFRAME MAP CONTAINER */
    .map-container {
        width: 100%;
        max-width: 580px;
        height: 380px; /* Ditentukan tingginya agar proporsional */
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #33291c;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
        margin-top: 15px;
    }

    /* Styling Iframe agar bersih tanpa border bawaan browser */
    .map-frame {
        width: 100%;
        height: 100%;
        border: none;
        background-color: #0c0c0c;
    }
    </style>
</head>

<body>

    <div class="container">
        <nav class="nav-sim">
            <div class="logo">PetCareHub</div>
            <ul class="menu">
                <li><a href="home.php">HOME</a></li>
                <li><a href="tentang.php">ABOUT US</a></li>
                <li><a href="service.php">SERVICES</a></li>
                <li><a href="produk.php">PRODUCT</a></li>
                <li class="active"><a href="kontak.php">CONTACT</a></li>
            </ul>
            
            <?php if (isset($_SESSION['username'])): ?>
                <a href="logout.php" class="btn-logout" onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?');">LOGOUT</a>
            <?php else: ?>
                <a href="login.php" class="btn-gs">GET STARTED</a>
            <?php endif; ?>
        </nav>

        <main class="contact-section">
            <div class="sub-title">Jangan Ragu Untuk Bertanya</div>
            <h1 class="main-title">Hubungi Kami <em>(Contact Us)</em></h1>

            <div class="contact-grid">
                <div class="grid-left">
                    <div class="contact-block">
                        <div class="info-header">
                            <span class="info-icon">📍</span>
                            <h2 class="info-label">Alamat Kami</h2>
                        </div>
                        <p class="info-text">
                            Jl. Margonda No.123, Depok, Kec. Pancoran Mas, Kota Depok, Jawa Barat 16424, Indonesia.
                        </p>
                    </div>

                    <div class="map-container">
                        <iframe src="peta.html" class="map-frame" title="Peta Lokasi PetCareHub"></iframe>
                    </div>
                </div>

                <div class="grid-right" style="padding-top: 5px;">
                    <div class="contact-block" style="margin-bottom: 45px;">
                        <div class="info-header">
                            <span class="info-icon">📞</span>
                            <h2 class="info-label">No Telepon & Email</h2>
                        </div>
                        <p class="info-text">
                            (021) 1234 5678 <span class="info-text-highlight">(Klinik & Grooming)</span><br>
                            (021) 8765 4321 <span class="info-text-highlight">(Umum)</span>
                        </p>
                    </div>

                    <div class="contact-block" style="margin-bottom: 45px;">
                        <div class="info-header">
                            <span class="info-icon">✉️</span>
                            <h2 class="info-label">Email Resmi</h2>
                        </div>
                        <p class="info-text">
                            info@petcarehub.com
                        </p>
                    </div>

                    <div class="contact-block">
                        <div class="info-header">
                            <span class="info-icon">📅</span>
                            <h2 class="info-label">Jam Operasional</h2>
                        </div>
                        <p class="info-text">
                            Senin - Jumat: 08:00 - 20:00<br>
                            Sabtu: 08:00 - 18:00<br>
                            Minggu: 09:00 - 17:00 <span class="info-text-highlight">(Hanya Keadaan Darurat Klinik)</span>
                        </p>
                    </div>
                </div>
            </div>
        </main>
    </div>

</body>
</html>