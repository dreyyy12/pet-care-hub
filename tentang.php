<?php
session_start();
date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - About Us</title>
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

    /* STORY SECTION STYLES */
    .story-section {
        display: flex;
        gap: 50px;
        align-items: flex-start;
        margin-bottom: 80px;
    }

    .story-left {
        flex: 1;
    }

    .story-right {
        flex: 1;
        padding-top: 50px;
    }

    .welcome-text {
        font-size: 11pt;
        text-transform: uppercase;
        color: #a3a3a1;
        letter-spacing: 3px;
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
    }

    .welcome-text::before {
        content: "";
        display: inline-block;
        width: 40px;
        height: 1px;
        background-color: #e5c185;
    }

    .section-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 36pt;
        font-weight: 500;
        line-height: 1.2;
        color: #ffffff;
        margin-bottom: 25px;
    }

    .section-title em {
        font-style: italic;
        color: #e5c185;
        font-family: inherit;
    }

    .story-img-container {
        width: 100%;
        border-radius: 4px;
        overflow: hidden;
        border: 1px solid #1c1c19;
    }

    .story-img-container img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }

    .story-p {
        font-size: 12pt;
        color: #a3a3a1;
        line-height: 1.8;
        margin-bottom: 25px;
        letter-spacing: 0.3px;
    }

    /* CHIPS / BADGES GRID */
    .why-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
        margin-top: 30px;
    }

    .why-box {
        background: #090909;
        border: 1px solid #221a10;
        border-radius: 8px;
        padding: 15px;
    }

    .why-top-row {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 8px;
    }

    .why-icon {
        color: #e5c185;
        font-size: 16pt;
    }

    .why-label {
        font-size: 10pt;
        font-weight: 700;
        color: #e5c185;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .why-desc {
        font-size: 9.5pt;
        color: #777777;
        line-height: 1.4;
    }

    /* VISI MISI STYLES */
    .center-heading {
        text-align: center;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 32pt;
        color: #ffffff;
        margin-bottom: 40px;
        margin-top: 40px;
    }

    .visi-misi-grid {
        display: flex;
        gap: 30px;
        margin-bottom: 80px;
    }

    .vm-card {
        flex: 1;
        background: #000;
        border: 1px solid #1c1c19;
        border-radius: 8px;
        padding: 40px;
        text-align: center;
        transition: border-color 0.3s;
    }

    .vm-card:hover {
        border-color: #6d5b43;
    }

    .vm-icon {
        font-size: 32pt;
        color: #e5c185;
        margin-bottom: 15px;
    }

    .vm-title {
        font-size: 20pt;
        font-family: 'Playfair Display', serif;
        color: #e5c185;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }

    .vm-text {
        font-size: 11pt;
        color: #a3a3a1;
        line-height: 1.6;
    }

    .misi-list {
        text-align: left;
        list-style: none;
        display: flex;
        flex-direction: column;
        gap: 12px;
        max-width: 450px;
        margin: 0 auto;
    }

    .misi-list li {
        font-size: 10.5pt;
        color: #a3a3a1;
        line-height: 1.5;
        position: relative;
        padding-left: 25px;
    }

    .misi-list li::before {
        content: "✓";
        position: absolute;
        left: 0;
        color: #e5c185;
        font-weight: bold;
    }

    /* REASONS HIGHLIGHT */
    .reason-banner {
        display: flex;
        gap: 25px;
        margin-bottom: 80px;
    }

    .reason-box {
        flex: 1;
        background: #090909;
        border: 1px solid #1c1c19;
        border-radius: 8px;
        padding: 20px 25px;
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .reason-circle {
        width: 45px;
        height: 45px;
        border-radius: 50%;
        background: #1c140a;
        border: 1px solid #e5c185;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #e5c185;
        font-weight: bold;
        flex-shrink: 0;
    }

    .reason-txt {
        font-size: 10.5pt;
        color: #a3a3a1;
        line-height: 1.5;
    }

    /* FOUNDERS STYLES */
    /* MEET THE FOUNDERS STYLES */
    .founders-grid {
        display: flex;
        justify-content: center;
        /* Membuat barisan card otomatis kumpul di tengah */
        align-items: center;
        gap: 40px;
        /* Jarak antar kolom founder, silakan sesuaikan lebarnya */
        width: 100%;
        margin: 0 auto 60px auto;
    }

    .founder-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        /* Membuat konten di dalam card (foto & nama) rata tengah */
        text-align: center;
    }

    .founder-avatar {
        width: 160px;
        /* Ukuran diameter lingkaran foto */
        height: 160px;
        border-radius: 50%;
        /* Membuat kotak menjadi lingkaran sempurna */
        overflow: hidden;
        margin-bottom: 15px;
        border: 3px solid #1c1c19;
        /* Garis tepi melingkar */
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.6);
        transition: border-color 0.3s ease;
    }

    /* Efek opsional: Lingkaran menyala sedikit saat kursor lewat */
    .founder-card:hover .founder-avatar {
        border-color: #e5c185;
    }

    .founder-avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        /* Mencegah gambar menjadi gepeng atau distorsi */
        display: block;
    }

    .founder-name {
        font-size: 12pt;
        font-weight: 600;
        color: #ffffff;
        letter-spacing: 0.5px;
        margin-top: 5px;
    }
    </style>
</head>

<body>

    <div class="container">
        <nav class="nav-sim">
            <div class="logo">PetCareHub</div>
            <ul class="menu">
                <li><a href="home.php">HOME</a></li>
                <li class="active"><a href="tentang.php">ABOUT US</a></li>
                <li><a href="service.php">SERVICES</a></li>
                <li><a href="produk.php">PRODUCT</a></li>
                <li><a href="kontak.php">CONTACT</a></li>
            </ul>

            <?php if (isset($_SESSION['username'])): ?>
            <a href="logout.php" class="btn-logout"
                onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?');">LOGOUT</a>
            <?php else: ?>
            <a href="login.php" class="btn-gs">GET STARTED</a>
            <?php endif; ?>
        </nav>

        <section class="story-section">
            <div class="story-left">
                <div class="welcome-text">Welcome to PetCareHub</div>
                <h1 class="section-title">Cerita Kami <em>(Our Story)</em></h1>
                <div class="story-img-container">
                    <img src="https://images.unsplash.com/photo-1581888227599-779811939961?q=80&w=800&auto=format&fit=crop"
                        alt="PetCareHub Tim Medis">
                </div>
            </div>

            <div class="story-right">
                <p class="story-p">
                    Berawal dari kecintaan yang mendalam, PetCareHub didirikan untuk menjadi rumah bagi kebahagiaan
                    hewan Anda.
                </p>
                <p class="story-p">
                    Kami adalah tim ahli yang berdedikasi mendekatkan PetCareHub selaku untuk hewan peliharaan, mulai
                    dari layanan perlengkapan berkualitas untuk menjaga kenyamanan dan peliharaan hewan Anda.
                </p>

                <p class="why-label" style="margin-bottom: -10px; margin-top: 40px; color: #ffffff;">Mengapa Memilih
                    Kami</p>

                <div class="why-grid">
                    <div class="why-box">
                        <div class="why-top-row">
                            <span class="why-icon">👤</span>
                            <span class="why-label">Tim Ahli & Sertifikasi</span>
                        </div>
                        <p class="why-desc">Tim ahli & Tersertifikasi Animal yang terbaik untuk hewan anda.</p>
                    </div>

                    <div class="why-box">
                        <div class="why-top-row">
                            <span class="why-icon">🏥</span>
                            <span class="why-label">Fasilitas Modern & Higienis</span>
                        </div>
                        <p class="why-desc">Perawatan Modern & Higienis, dengan konsultasi anda.</p>
                    </div>

                    <div class="why-box">
                        <div class="why-top-row">
                            <span class="why-icon">💼</span>
                            <span class="why-label">Layanan Komprehensif</span>
                        </div>
                        <p class="why-desc">Layanan Komprehensif untuk Informasi & Pendidikan hewan anda.</p>
                    </div>

                    <div class="why-box">
                        <div class="why-top-row">
                            <span class="why-icon">🐾</span>
                            <span class="why-label">Pencegahan Penyakit Terpadu</span>
                        </div>
                        <p class="why-desc">Pencegahan Penyakit Terpadu & Menangani anabul anda.</p>
                    </div>
                </div>
            </div>
        </section>

        <h2 class="center-heading">Visi & Misi</h2>

        <section class="visi-misi-grid">
            <div class="vm-card">
                <div class="vm-icon">🔬</div>
                <h3 class="vm-title">Visi</h3>
                <p class="vm-text">Untuk menjadi layanan care center yang modern, terpercaya, dan penuh kasih sayang
                    dalam memberikan perawatan terbaik bagi hewan peliharaan Anda.</p>
            </div>

            <div class="vm-card">
                <div class="vm-icon">🤝</div>
                <h3 class="vm-title">Misi</h3>
                <ul class="misi-list">
                    <li>Memberikan perawatan layanan unggul dan pendidikan pelayanan penuh kasih</li>
                    <li>Layanan Medis Unggul</li>
                    <li>Edukasi Pemilik</li>
                    <li>Perawatan Penuh Kasih</li>
                </ul>
            </div>
        </section>

        <h2 class="center-heading" style="font-size: 20pt; margin-bottom: 25px;">Mengapa memilih kami</h2>

        <section class="reason-banner">
            <div class="reason-box">
                <div class="reason-circle">✓</div>
                <p class="reason-txt">Layanan Medis Unggul, demi mewujudkan kebahagiaan hewan Anda</p>
            </div>
            <div class="reason-box">
                <div class="reason-circle">🐾</div>
                <p class="reason-txt">Perawatan Penuh Kasih, mendukung kesehatan dan mewujudkan kebahagiaan hewan Anda
                </p>
            </div>
        </section>

        <h2 class="center-heading" style="font-size: 22pt; margin-bottom: 45px;">Meet the founders</h2>

        <section class="founders-grid">
            <div class="founder-card">
                <div class="founder-avatar">
                    <img src="images/puspa.jpeg" alt="drh. Puspa G.">
                </div>
                <p class="founder-name">drh. Puspa G.</p>
            </div>

            <div class="founder-card">
                <div class="founder-avatar">
                    <img src="images/pani.jpeg" alt="drh. Stephanie C.">
                </div>
                <p class="founder-name">drh. Stephanie C.</p>
            </div>

            <div class="founder-card">
                <div class="founder-avatar">
                    <img src="images/ezra.jpeg" alt="drh. Ezra J.">
                </div>
                <p class="founder-name">drh. Ezra J.</p>
            </div>

            <div class="founder-card">
                <div class="founder-avatar">
                    <img src="images/dre.jpeg" alt="drh. Audrey C.">
                </div>
                <p class="founder-name">drh. Audrey C.</p>
            </div>
        </section>

    </div>

</body>

</html>