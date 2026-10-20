<?php
session_start();

// Proteksi Halaman: Jika tidak ada session login, kembalikan ke login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// AMANKAN REAL-TIME INTERACTION AJAX FETCH
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['set_metode'])) {
    $_SESSION['metode_pembayaran'] = $_POST['set_metode'];
    exit(); 
}

date_default_timezone_set('Asia/Jakarta');
if (!isset($_SESSION['no_nota'])) {
    $_SESSION['no_nota'] = time() . "_" . rand(1000, 9999);
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "softdev";
$conn = new mysqli($host, $user, $pass, $db);

$grand_total = 0;

// ====================================================================
// PENYESUAIAN LOGIKA MULTI-TRANSAKSI (PRODUK / GROOMING / HOTEL / KONSUL)
// ====================================================================
if (isset($_SESSION['jenis_transaksi']) && $_SESSION['jenis_transaksi'] !== 'produk') {
    
    // Jika datang dari layanan (grooming/hotel/konsul), ambil nominal dari data Session operan!
    $grand_total = isset($_SESSION['grand_total']) ? $_SESSION['grand_total'] : 0;

} else {
    
    // Default alur lama: Hitung total belanja produk murni dari database transaksi_produk
    if (!$conn->connect_error) {
        $token_aktif = $_SESSION['no_nota'];
        $check_column = $conn->query("SHOW COLUMNS FROM transaksi_produk LIKE 'no_nota'");
        
        if ($check_column && $check_column->num_rows > 0) {
            $sql_get = "SELECT SUM(total_harga) AS total FROM transaksi_produk WHERE no_nota = '$token_aktif'";
        } else {
            $sql_get = "SELECT SUM(total_harga) AS total FROM (SELECT total_harga FROM transaksi_produk ORDER BY id DESC LIMIT 5) AS temp";
        }
        
        $result_get = $conn->query($sql_get);
        if ($result_get) {
            $row = $result_get->fetch_assoc();
            $grand_total = isset($row['total']) ? $row['total'] : 0;
        }
    }
}

if (!$conn->connect_error) {
    $conn->close();
}

$besok = date('d F Y, H:i', strtotime('+24 hours')) . " WIB";
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Payment Option</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body {
        background-color: #000000;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 20px;
    }

    .payment-container {
        width: 100%;
        max-width: 650px;
        text-align: center;
    }

    h1 {
        font-size: 36px;
        font-weight: 600;
        color: #ffffff;
        margin-bottom: 40px;
        letter-spacing: 0.5px;
    }

    .options-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-bottom: 50px;
    }

    .option-wrapper {
        background-color: #0c0c0c;
        border: 1px solid #33291c;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s ease;
    }

    .option-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 25px;
        cursor: pointer;
        transition: all 0.2s ease;
        text-decoration: none;
    }

    .option-text {
        font-size: 13px;
        font-weight: 700;
        color: #8c7557;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .option-wrapper:hover {
        border-color: #bfa37a;
    }

    .option-wrapper:hover .option-text {
        color: #e5c38c;
    }

    .chevron {
        width: 10px;
        height: 10px;
        border-left: 2px solid #8c7557;
        border-top: 2px solid #8c7557;
        transform: rotate(225deg);
        margin-top: -3px;
        transition: transform 0.3s ease, border-color 0.2s ease;
    }

    .option-wrapper:hover .chevron {
        border-color: #e5c38c;
    }

    .option-wrapper.active .chevron {
        transform: rotate(45deg);
        margin-top: 5px;
        border-color: #e5c38c;
    }

    .dropdown-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease-out, padding 0.3s ease;
        padding: 0 25px;
        text-align: left;
    }

    .option-wrapper.active .dropdown-content {
        max-height: 600px;
        padding-top: 15px;
        padding-bottom: 25px;
    }

    .input-row {
        margin-bottom: 15px;
        display: flex;
        align-items: center;
    }

    .input-row label {
        width: 160px;
        font-size: 14px;
        color: #888888;
    }

    .input-double {
        display: flex;
        gap: 15px;
    }

    .input-double .input-row {
        flex: 1;
        margin-bottom: 0;
    }

    .dropdown-content input {
        flex: 1;
        background-color: #141414;
        border: 1px solid #33291c;
        border-radius: 10px;
        padding: 16px 20px;
        color: #ffffff;
        font-size: 14px;
        outline: none;
        transition: border-color 0.2s ease;
    }

    .dropdown-content input::placeholder {
        color: #555555;
    }

    .dropdown-content input:focus {
        border-color: #bfa37a;
    }

    .qris-container {
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 20px 0 5px 0;
        width: 100%;
    }

    .qris-image {
        max-width: 320px;
        width: 100%;
        height: auto;
        max-height: 320px;
        object-fit: contain;
        border-radius: 10px;
        border: 1px solid #33291c;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.6);
    }

    .btn-back {
        display: inline-block;
        background-color: #26262b;
        color: #ffffff;
        text-decoration: none;
        padding: 12px 45px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 1px;
        text-transform: uppercase;
        border: 1px solid #3a3a3f;
        transition: all 0.2s ease;
    }

    .btn-back:hover {
        background-color: #333338;
        border-color: #55555a;
    }
    </style>
</head>

<body>

    <div class="payment-container">
        <h1>Payment Option</h1>

        <div class="options-list">

            <div class="option-wrapper" id="cc-wrapper">
                <div class="option-item" onclick="toggleDropdown('cc-wrapper')">
                    <span class="option-text">1. CREDIT / DEBIT CARD</span>
                    <span class="chevron"></span>
                </div>
                <div class="dropdown-content">
                    <div class="input-row">
                        <input type="text" placeholder="Card Number">
                    </div>
                    <div class="input-row">
                        <input type="text" placeholder="Nama Pemilik Kartu">
                    </div>
                    <div class="input-double">
                        <div class="input-row">
                            <input type="text" placeholder="Expiry Date">
                        </div>
                        <div class="input-row">
                            <input type="text" placeholder="CVV">
                        </div>
                    </div>
                </div>
            </div>

            <div class="option-wrapper" id="va-wrapper">
                <div class="option-item" onclick="toggleDropdown('va-wrapper')">
                    <span class="option-text">2. BANK TRANSFER / VIRTUAL ACCOUNT</span>
                    <span class="chevron"></span>
                </div>
                <div class="dropdown-content">
                    <div class="input-row">
                        <label>Total Pembayaran</label>
                        <input type="text" value="Rp. <?php echo number_format($grand_total, 0, ',', '.'); ?>" readonly>
                    </div>
                    <div class="input-row">
                        <label>Nomor Virtual Account</label>
                        <input type="text" value="8252<?php echo rand(1000, 9999); ?>0057<?php echo rand(10, 99); ?>" readonly>
                    </div>
                    <div class="input-row">
                        <label>Bayar Dalam</label>
                        <input type="text" value="<?php echo $besok; ?>" readonly>
                    </div>
                </div>
            </div>

            <div class="option-wrapper" id="qris-wrapper">
                <div class="option-item" onclick="toggleDropdown('qris-wrapper')">
                    <span class="option-text">3. E-WALLET (QRIS)</span>
                    <span class="chevron"></span>
                </div>
                <div class="dropdown-content">
                    <div class="input-row">
                        <label>Total Pembayaran</label>
                        <input type="text" value="Rp. <?php echo number_format($grand_total, 0, ',', '.'); ?>" readonly>
                    </div>
                    <div class="input-row">
                        <label>Bayar Dalam</label>
                        <input type="text" value="<?php echo $besok; ?>" readonly>
                    </div>
                    <div class="qris-container">
                        <img src="images/qris.png" alt="QRIS Code" class="qris-image">
                    </div>
                </div>
            </div>

        </div>

        <a href="kwitansi.php" class="btn-back">Sudah Membayar</a>
    </div>

    <script>
    function toggleDropdown(wrapperId) {
        var wrappers = document.querySelectorAll('.option-wrapper');
        var metodeDipilih = "";

        wrappers.forEach(function(w) {
            if (w.id === wrapperId) {
                w.classList.toggle('active');
                if (w.classList.contains('active')) {
                    if (wrapperId === 'cc-wrapper') metodeDipilih = "CREDIT / DEBIT CARD";
                    if (wrapperId === 'va-wrapper') metodeDipilih = "VIRTUAL ACCOUNT";
                    if (wrapperId === 'qris-wrapper') metodeDipilih = "QRIS";
                }
            } else {
                w.classList.remove('active');
            }
        });

        if (metodeDipilih !== "") {
            fetch('', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: 'set_metode=' + encodeURIComponent(metodeDipilih)
            });
        }
    }
    </script>
</body>
</html>