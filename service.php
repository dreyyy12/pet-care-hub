<?php
session_start();
if (!isset($_SESSION['username'])) {
    // Mengambil nama file yang sedang diakses saat ini (misal: service.php atau produk.php)
    $halaman_ini = basename($_SERVER['PHP_SELF']); 
    
    // Alihkan ke login sambil membawa titipan (?redirect=service.php)
    header("Location: login.php?redirect=" . $halaman_ini);
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Premium Product Service</title>
    <style>
    /* Reset & Base Style */
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
        /* Menghilangkan garis bawah link */
        display: inline-block;
    }

    .btn-logout:hover {
        background: #a71d2a;
        color: #ffffff;
    }

    /* GLOBAL HEADERS */
    .grooming-section,
    .hotel-section,
    .konsul-section {
        text-align: center;
        width: 100%;
    }

    .hotel-section,
    .konsul-section {
        margin-top: 100px;
    }

    .grooming-header h2,
    .hotel-header h2,
    .konsul-header h2 {
        font-family: 'Playfair Display', serif;
        font-weight: 400;
        font-size: 28px;
        margin-bottom: 10px;
        color: #f5f5f3;
    }

    .grooming-header h1,
    .hotel-header h1,
    .konsul-header h1 {
        font-family: 'Playfair Display', serif;
        font-size: 42px;
        font-weight: 600;
        margin-bottom: 50px;
        letter-spacing: 0.5px;
    }

    .grooming-header .highlight,
    .hotel-header .highlight,
    .konsul-header .highlight {
        font-style: italic;
        color: #e5c185;
        font-weight: 400;
        font-family: 'Playfair Display', serif;
    }

    .services-grid {
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 40px;
        flex-wrap: wrap;
    }

    .service-card {
        flex: 1;
        min-width: 240px;
        max-width: 280px;
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        transition: transform 0.3s ease;
    }

    .service-card:hover {
        transform: translateY(-5px);
    }

    .card-link {
        text-decoration: none;
        cursor: pointer;
        display: block;
        border-radius: 50%;
        margin-bottom: 25px;
    }

    .image-circle {
        width: 180px;
        height: 180px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #1c1c1a;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.5);
        transition: border-color 0.3s ease;
    }

    .card-link:hover .image-circle {
        border-color: #e5c185;
    }

    .image-circle img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .service-card h3 {
        font-size: 19px;
        font-weight: 700;
        line-height: 1.4;
        margin-bottom: 15px;
        color: #ffffff;
        letter-spacing: 0.3px;
    }

    .service-card p {
        font-size: 14px;
        color: #a3a3a1;
        line-height: 1.6;
        padding: 0 5px;
    }

    .action-buttons-container {
        margin-top: 90px;
        margin-bottom: 40px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 25px;
        width: 100%;
    }

    .action-row-top {
        display: flex;
        justify-content: center;
        gap: 30px;
        width: 100%;
        flex-wrap: wrap;
    }

    .btn-action {
        background-color: #e5c38c;
        color: #000000;
        border: none;
        padding: 18px 40px;
        font-size: 14px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-radius: 10px;
        cursor: pointer;
        min-width: 290px;
        text-align: center;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
        transition: background-color 0.2s ease, transform 0.1s ease;
        display: inline-block;
        text-decoration: none;
    }

    .btn-action:hover {
        background-color: #d4b27b;
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
                <li class="active"><a href="service.php">SERVICES</a></li>
                <li><a href="produk.php">PRODUCT</a></li>
                <li><a href="kontak.php">CONTACT</a></li>
            </ul>
            <a href="logout.php" class="btn-logout"
                onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?');">LOGOUT</a>
        </nav>

        <main class="grooming-section">
            <header class="grooming-header">
                <h2>Pet Service Hub</h2>
                <h1>Layanan <span class="highlight">Grooming</span> Profesional</h1>
            </header>

            <div class="services-grid">
                <div class="service-card">
                    <a href="grooming.php?paket=mandi" class="card-link">
                        <div class="image-circle">
                            <img src="img/layanan2.jpeg"
                                alt="Mandi & Spa Bulu Lembut">
                        </div>
                    </a>
                    <h3>Mandi & Spa<br>Bulu Lembut</h3>
                    <p>Perawatan mandi air hangat dengan shampo premium</p>
                </div>

                <div class="service-card">
                    <a href="grooming.php?paket=kuku" class="card-link">
                        <div class="image-circle">
                            <img src="img/layanan1.jpg"
                                alt="Gunting Kuku Aman & Nyaman">
                        </div>
                    </a>
                    <h3>Gunting Kuku<br>Aman & Nyaman</h3>
                    <p>Perawatan mandi air hangat dengan shampo premium</p>
                </div>

                <div class="service-card">
                    <a href="grooming.php?paket=cukur" class="card-link">
                        <div class="image-circle">
                            <img src="img/layanan3.jpeg"
                                alt="Pencukuran Bulu Rapi">
                        </div>
                    </a>
                    <h3>Pencukuran<br>Bulu Rapi</h3>
                    <p>Perawatan mandi air hangat dengan shampo premium</p>
                </div>

                <div class="service-card">
                    <a href="grooming.php?paket=telinga" class="card-link">
                        <div class="image-circle">
                            <img src="img/layanan4.jpeg"
                                alt="Pembersih Telinga & Mata">
                        </div>
                    </a>
                    <h3>Pembersih<br>Telinga & Mata</h3>
                    <p>Perawatan mandi air hangat dengan shampo premium</p>
                </div>
            </div>
        </main>

        <main class="hotel-section">
            <header class="hotel-header">
                <h2>Pet Stay Hub</h2>
                <h1>Layanan <span class="highlight">Pet</span> Hotel</h1>
            </header>

            <div class="services-grid">
                <div class="service-card">
                    <a href="hotel.php?kamar=small" class="card-link">
                        <div class="image-circle">
                            <img src="img/hotel3.jpeg"
                                alt="Small Room">
                        </div>
                    </a>
                    <h3>Small Room</h3>
                    <p>Kamar aman dan nyaman, ideal untuk anabul ukuran kecil agar tetap cozy.</p>
                </div>

                <div class="service-card">
                    <a href="hotel.php?kamar=medium" class="card-link">
                        <div class="image-circle">
                            <img src="img/hotel1.jpeg"
                                alt="Medium Room">
                        </div>
                    </a>
                    <h3>Medium Room</h3>
                    <p>Ruang gerak lebih luas, lengkap dengan mainan dan kasur empuk.</p>
                </div>

                <div class="service-card">
                    <a href="hotel.php?kamar=large" class="card-link">
                        <div class="image-circle">
                            <img src="img/hotel2.jpeg"
                                alt="Large Room Suite">
                        </div>
                    </a>
                    <h3>Large Room / Suite</h3>
                    <p>Area privat super luas, cocok untuk ras besar atau penitipan multi-pet.</p>
                </div>
            </div>
        </main>

        <main class="konsul-section">
            <header class="konsul-header">
                <h2>Pet Health Care</h2>
                <h1>Konsultasi <span class="highlight">Dokter</span> Hewan</h1>
            </header>

            <div class="services-grid">
                <div class="service-card">
                    <a href="konsul.php?paket=checkup" class="card-link">
                        <div class="image-circle">
                            <img src="img/dokter1.jpg"
                                alt="Medical Check-up">
                        </div>
                    </a>
                    <h3>Medical Check-up</h3>
                    <p>Pemeriksaan kesehatan menyeluruh untuk mendeteksi dini masalah kesehatan anabul.</p>
                </div>

                <div class="service-card">
                    <a href="konsul.php?paket=nutrisi" class="card-link">
                        <div class="image-circle">
                            <img src="img/dokter2.jpg"
                                alt="Konsultasi Nutrisi">
                        </div>
                    </a>
                    <h3>Konsultasi Nutrisi</h3>
                    <p>Atur pola makan dan diet seimbang yang tepat sesuai dengan kebutuhan gizi anabul.</p>
                </div>

                <div class="service-card">
                    <a href="konsul.php?paket=umum" class="card-link">
                        <div class="image-circle">
                            <img src="img/dokter3.jpg"
                                alt="Konsultasi Umum">
                        </div>
                    </a>
                    <h3>Konsultasi Umum</h3>
                    <p>Tanya jawab langsung mengenai keluhan harian atau perilaku anabul kesayangan Anda.</p>
                </div>
            </div>
        </main>

        <div class="action-buttons-container">
            <div class="action-row-top">
                <a href="grooming.php" class="btn-action">Buat Jadwal Grooming</a>
                <a href="hotel.php" class="btn-action">Booking Pet Hotel</a>
            </div>
            <a href="konsul.php" class="btn-action">Konsultasi Klinik</a>
        </div>
    </div>

</body>

</html>