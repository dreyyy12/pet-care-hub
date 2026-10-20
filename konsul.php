<?php 
ob_start();
session_start();
date_default_timezone_set('Asia/Jakarta');

// Proteksi halaman: Pastikan customer sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "softdev";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi ke Database Gagal: " . $conn->connect_error);
}

// Logika penanganan saat tombol "SELESAI" diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_konsul'])) {
    $layanan       = $conn->real_escape_string($_POST['layanan']);
    $harga_konsul  = floatval($_POST['harga_layanan']); 
    $nama_hewan    = $conn->real_escape_string($_POST['nama_hewan']);
    $jenis_hewan   = $conn->real_escape_string($_POST['jenis_hewan']);
    $jenis_ras     = $conn->real_escape_string($_POST['jenis_ras']);
    $jenis_kelamin = $conn->real_escape_string($_POST['jenis_kelamin']);
    $umur          = $conn->real_escape_string($_POST['umur']);
    $berat_badan   = $conn->real_escape_string($_POST['berat_badan']);
    $status_vaksin = $conn->real_escape_string($_POST['status_vaksin']);
    $alergi        = $conn->real_escape_string($_POST['alergi']);
    $behavior      = $conn->real_escape_string($_POST['behavior']);
    
    // HANYA MENANGKAP JADWAL & JAM KONSUL (Checkout dihapus)
    $jadwal_konsul = $conn->real_escape_string($_POST['jadwal_tgl']);
    $jam_konsul    = $conn->real_escape_string($_POST['jadwal_jam']);
    $username_user = $_SESSION['username'];

    $sql = "INSERT INTO booking_konsultasi (username, paket_konsul, harga, nama_hewan, jenis_hewan, tanggal_konsul, jam_konsul) 
            VALUES ('$username_user', '$layanan', '$harga_konsul', '$nama_hewan', '$jenis_hewan', '$jadwal_konsul', '$jam_konsul')";
    $conn->query($sql);

    $_SESSION['jenis_transaksi'] = 'konsul';
    $_SESSION['nama_layanan']    = $_POST['layanan']; 

    $_SESSION['detail_tambahan'] = "Nama Hewan: " . $_POST['nama_hewan'] . " | Jadwal Konsultasi: " . date('d F Y', strtotime($_POST['jadwal_tgl'])) . " Pukul " . $_POST['jadwal_jam'] . " WIB";

    $_SESSION['grand_total']     = floatval($_POST['harga_layanan']);
    $conn->close();

    header("Location: metode.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Konsultasi Dokter</title>
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

    .btn-gs {
        background: #e5c185;
        color: #000;
        padding: 12px 28px;
        border-radius: 8px;
        font-weight: bold;
        font-size: 10pt;
        cursor: pointer;
        letter-spacing: 0.5px;
    }

    .booking-container {
        max-width: 900px;
        width: 100%;
        margin: 0 auto;
        padding: 20px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 25px 40px;
        margin-bottom: 40px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        text-align: left;
    }

    .form-group label {
        font-size: 11pt;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        background-color: #cccccc;
        border: none;
        border-radius: 8px;
        padding: 16px 20px;
        color: #333333;
        font-size: 12pt;
        font-weight: 500;
        outline: none;
        transition: all 0.3s ease;
    }

    .form-group input[readonly] {
        background-color: #b3b3b3;
        color: #111111;
        font-weight: 700;
    }

    .form-group input::placeholder {
        color: #777777;
    }

    .form-group select {
        cursor: pointer;
        appearance: none;
        background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 10 10'><path fill='%23333333' d='M0 3l5 5 5-5z'/></svg>");
        background-repeat: no-repeat;
        background-position: right 20px center;
    }

    .schedule-title {
        grid-column: span 2;
        text-align: center;
        font-size: 13pt;
        font-weight: 700;
        color: #ffffff;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin: 20px 0 10px 0;
    }

    .submit-container {
        width: 100%;
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .btn-submit-booking {
        background: linear-gradient(180deg, #e5c38c 0%, #bfa37a 100%);
        color: #000000;
        border: none;
        padding: 18px 0;
        width: 100%;
        max-width: 450px;
        font-size: 13pt;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1.5px;
        border-radius: 8px;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(229, 195, 140, 0.2);
        transition: all 0.2s ease;
    }

    .btn-submit-booking:hover {
        background: linear-gradient(180deg, #f0d4ab 0%, #d4b27b 100%);
        box-shadow: 0 4px 20px rgba(229, 195, 140, 0.4);
        transform: translateY(-1px);
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
            <div class="btn-gs">GET STARTED</div>
        </nav>

        <main class="booking-container">
            <form action="" method="POST">
                <div class="form-grid">

                    <div class="form-group">
                        <label>Pilih Layanan Konsultasi</label>
                        <?php $get_paket = isset($_GET['paket']) ? $_GET['paket'] : ''; ?>
                        <select name="layanan" id="pilihan-layanan" onchange="updateHarga()" required>
                            <option value="" disabled <?php echo ($get_paket == '') ? 'selected' : ''; ?>>Pilih Paket Konsultasi</option>
                            <option value="Medical Check-up" <?php echo ($get_paket == 'checkup') ? 'selected' : ''; ?>>Medical Check-up</option>
                            <option value="Konsultasi Nutrisi" <?php echo ($get_paket == 'nutrisi') ? 'selected' : ''; ?>>Konsultasi Nutrisi</option>
                            <option value="Konsultasi Umum" <?php echo ($get_paket == 'umum') ? 'selected' : ''; ?>>Konsultasi Umum</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Biaya Konsultasi</label>
                        <input type="text" id="harga-tampil" value="Rp. 0" readonly>
                        <input type="hidden" name="harga_layanan" id="harga-asli" value="0">
                    </div>

                    <div class="form-group">
                        <label>Umur Hewan</label>
                        <input type="text" name="umur" placeholder="Contoh : 1 Tahun" required>
                    </div>

                    <div class="form-group">
                        <label>Nama Hewan</label>
                        <input type="text" name="nama_hewan" placeholder="Contoh : Bobi" required>
                    </div>

                    <div class="form-group">
                        <label>Berat Badan</label>
                        <input type="text" name="berat_badan" placeholder="Contoh : 5 Kg" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Hewan</label>
                        <select name="jenis_hewan" required>
                            <option value="" disabled selected>Pilih Jenis</option>
                            <option value="Kucing">Kucing</option>
                            <option value="Anjing">Anjing</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Status Vaksin</label>
                        <input type="text" name="status_vaksin" placeholder="Contoh : Sudah Vaksin Tahap 2" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Ras</label>
                        <input type="text" name="jenis_ras" placeholder="Contoh : Anggora / Persian" required>
                    </div>

                    <div class="form-group">
                        <label>Alergi</label>
                        <input type="text" name="alergi" placeholder="Contoh : Alergi Ayam / Tidak Ada" required>
                    </div>

                    <div class="form-group">
                        <label>Jenis Kelamin</label>
                        <input type="text" name="jenis_kelamin" placeholder="Contoh : Jantan / Betina" required>
                    </div>

                    <div class="form-group" style="grid-column: span 2;">
                        <label>Behavior / Perilaku</label>
                        <input type="text" name="behavior" placeholder="Contoh : Penurut / Agresif" required>
                    </div>

                    <div class="schedule-title">Pilih Jadwal Kedatangan Dokter</div>

                    <div class="form-group">
                        <label>Tanggal Konsultasi</label>
                        <input type="date" name="jadwal_tgl" required>
                    </div>

                    <div class="form-group">
                        <label>Jam Konsultasi</label>
                        <select name="jadwal_jam" required>
                            <option value="" disabled selected>Pilih Jam Pertemuan</option>
                            <option value="09:00">09:00 WIB</option>
                            <option value="11:00">11:00 WIB</option>
                            <option value="13:00">13:00 WIB</option>
                            <option value="15:00">15:00 WIB</option>
                            <option value="17:00">17:00 WIB</option>
                        </select>
                    </div>

                </div>

                <div class="submit-container">
                    <button type="submit" name="submit_konsul" class="btn-submit-booking">Selesai</button>
                </div>
            </form>
        </main>
    </div>

    <script>
    function updateHarga() {
        var paket = document.getElementById("pilihan-layanan").value;
        var inputTampil = document.getElementById("harga-tampil");
        var inputAsli = document.getElementById("harga-asli");
        var harga = 0;

        if (paket === "Medical Check-up") {
            harga = 250000;
        } else if (paket === "Konsultasi Nutrisi") {
            harga = 120000;
        } else if (paket === "Konsultasi Umum") {
            harga = 75000;
        }

        inputAsli.value = harga;

        if (harga > 0) {
            var formatRupiah = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(harga);

            inputTampil.value = formatRupiah.replace("Rp", "Rp. ");
        } else {
            inputTampil.value = "Rp. 0";
        }
    }

    window.onload = function() {
        updateHarga();
    };
    </script>
</body>
</html>