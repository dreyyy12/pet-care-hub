<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

// Proteksi halaman
if (!isset($_SESSION['username']) || !isset($_SESSION['no_nota'])) {
    header("Location: produk.php");
    exit();
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "softdev";
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi Gagal: " . $conn->connect_error);
}

// LOGIKA: PROSES PENGHAPUSAN PRODUK DARI KERANJANG
if (isset($_GET['action']) && $_GET['action'] === 'hapus_item' && isset($_GET['id_transaksi'])) {
    $id_hapus = intval($_GET['id_transaksi']);
    $token_aktif = $_SESSION['no_nota'];

    $get_nama = $conn->query("SELECT nama_produk FROM transaksi_produk WHERE id = $id_hapus AND no_nota = '$token_aktif'");
    if ($get_nama && $get_nama->num_rows > 0) {
        $row_nama = $get_nama->fetch_assoc();
        $nama_produk_hapus = $row_nama['nama_produk'];
        $cart_id = md5($nama_produk_hapus);

        if (isset($_SESSION['keranjang_belanja'][$cart_id])) {
            unset($_SESSION['keranjang_belanja'][$cart_id]);
        }
    }

    $conn->query("DELETE FROM transaksi_produk WHERE id = $id_hapus AND no_nota = '$token_aktif'");
    $conn->close();
    header("Location: output.php");
    exit();
}

$grand_total = 0;
$riwayat_item = [];

$token_aktif = $_SESSION['no_nota'];
$check_column = $conn->query("SHOW COLUMNS FROM transaksi_produk LIKE 'no_nota'");

if ($check_column && $check_column->num_rows > 0) {
    $sql_get = "SELECT * FROM transaksi_produk WHERE no_nota = '$token_aktif' ORDER BY id ASC";
} else {
    $sql_get = "SELECT * FROM transaksi_produk ORDER BY id DESC LIMIT 5";
}

$result_get = $conn->query($sql_get);

if ($result_get && $result_get->num_rows > 0) {
    while ($row = $result_get->fetch_assoc()) {
        $riwayat_item[] = $row;
        $grand_total += $row['total_harga'];
    }
}

// ========================================================
// LOGIKA BARU: VALIDASI SERVER AGAR TIDAK BISA SUBMIT JIKA KOSONG
// ========================================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['proses_pembayaran'])) {
    if (empty($riwayat_item) || $grand_total <= 0) {
        $error_keranjang_kosong = "Keranjang Anda kosong! Silakan pilih produk terlebih dahulu sebelum membayar.";
    } else {
        $_SESSION['alamat'] = $conn->real_escape_string($_POST['alamat']);
        $_SESSION['no_telp'] = $conn->real_escape_string($_POST['no_telp']);
        $_SESSION['catatan'] = $conn->real_escape_string($_POST['catatan']);
        $_SESSION['jasa_kirim'] = $conn->real_escape_string($_POST['jasa_kirim']);
        
        $conn->close();
        header("Location: metode.php");
        exit();
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub Premium Product Services - Output</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #0c0c0c;
            color: #ffffff;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 40px 20px;
        }

        .main-title {
            color: #e5c38c;
            font-size: 32px;
            font-weight: 500;
            margin-bottom: 30px;
            text-align: center;
            letter-spacing: 0.5px;
        }

        .output-container {
            background-color: #111111;
            border: 1px solid #222222;
            border-radius: 12px;
            width: 100%;
            max-width: 680px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.7);
        }

        .section-title {
            color: #bfa37a;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 1px;
            margin-bottom: 25px;
            text-transform: uppercase;
        }

        /* Style Alert Error Keranjang Kosong */
        .alert-error {
            background-color: #721c24;
            color: #f8d7da;
            border: 1px solid #f5c6cb;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
            font-size: 14px;
            text-align: center;
            font-weight: 600;
        }

        .cart-card {
            background-color: #161616;
            border: 1px solid #282828;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
        }

        .cart-card-title {
            font-size: 15px;
            font-weight: 700;
            color: #ffffff;
            border-bottom: 1px solid #282828;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .item-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 18px;
            border-bottom: 1px solid #1f1f1f;
            padding-bottom: 10px;
        }

        .item-left-side {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            flex: 1;
        }

        .item-info h3 {
            font-size: 15px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 4px;
        }

        .item-info p {
            font-size: 12px;
            color: #777777;
        }

        .item-right-side {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .item-price {
            font-size: 15px;
            font-weight: 600;
            color: #ffffff;
        }

        .btn-delete-item {
            color: #888888;
            text-decoration: none;
            font-size: 18px;
            font-weight: bold;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background-color: #222222;
            transition: all 0.2s ease;
        }

        .btn-delete-item:hover {
            background-color: #721c24;
            color: #f8d7da;
        }

        .divider {
            border: none;
            border-top: 1px dashed #444444;
            margin: 25px 0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .total-label {
            font-size: 13px;
            color: #888888;
            letter-spacing: 1px;
        }

        .total-amount {
            font-size: 24px;
            font-weight: 700;
            color: #e5c38c;
        }

        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .form-group label {
            display: block;
            font-size: 13px;
            color: #bfa37a;
            margin-bottom: 8px;
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            background-color: #161616;
            border: 1px solid #33291c;
            border-radius: 8px;
            padding: 14px 18px;
            color: #ffffff;
            font-size: 14px;
            outline: none;
            transition: all 0.3s ease;
        }

        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            border-color: #e5c38c;
            background-color: #1a1a1a;
        }

        .form-group textarea {
            resize: none;
            height: 80px;
        }

        .form-group select {
            cursor: pointer;
            appearance: none;
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='10' height='10' viewBox='0 0 10 10'><path fill='%23bfa37a' d='M0 3l5 5 5-5z'/></svg>");
            background-repeat: no-repeat;
            background-position: right 18px center;
        }

        .btn-complete {
            display: block;
            width: 100%;
            background-color: #e5c38c;
            color: #111111;
            padding: 16px 0;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s ease;
            margin-top: 10px;
            margin-bottom: 25px;
            text-align: center;
        }

        .btn-complete:hover {
            background-color: #d4b27b;
        }

        /* Style tombol ketika dimatikan karena keranjang kosong */
        .btn-complete:disabled {
            background-color: #222222;
            color: #555555;
            border: 1px solid #333333;
            cursor: not-allowed;
        }

        .back-link {
            display: block;
            text-align: center;
            color: #888888;
            text-decoration: none;
            font-size: 13px;
            transition: color 0.2s ease;
        }

        .back-link:hover {
            color: #ffffff;
        }
    </style>
</head>
<body>

    <h1 class="main-title">PetCareHub Premium Product Services</h1>

    <div class="output-container">
        <h2 class="section-title">Hasil Tampilan Akhir</h2>

        <?php if (isset($error_keranjang_kosong)): ?>
            <div class="alert-error"><?php echo $error_keranjang_kosong; ?></div>
        <?php endif; ?>

        <div class="cart-card">
            <div class="cart-card-title">Keranjang Belanja & Ringkasan Pembelian Terkini</div>
            
            <?php if (!empty($riwayat_item)): ?>
                <?php foreach ($riwayat_item as $item): 
                    $harga_satuan = $item['harga'];
                ?>
                <div class="item-row">
                    <div class="item-left-side">
                        <div class="item-info">
                            <h3><?php echo $item['qty']; ?>x <?php echo htmlspecialchars($item['nama_produk']); ?></h3>
                            <p>Harga Satuan: Rp.<?php echo number_format($harga_satuan, 0, ',', '.'); ?></p>
                        </div>
                    </div>
                    
                    <div class="item-right-side">
                        <div class="item-price">Rp.<?php echo number_format($item['total_harga'], 0, ',', '.'); ?></div>
                        <a href="output.php?action=hapus_item&id_transaksi=<?php echo $item['id']; ?>" 
                           class="btn-delete-item" 
                           onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini dari keranjang?');" 
                           title="Hapus Produk">&times;</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="color: #666; font-size: 14px; text-align: center; padding: 10px 0;">Belum ada transaksi di keranjang ini.</p>
            <?php endif; ?>

            <hr class="divider">

            <div class="total-row">
                <div class="total-label">TOTAL BELANJA:</div>
                <div class="total-amount">Rp.<?php echo number_format($grand_total, 0, ',', '.'); ?></div>
            </div>
        </div>

        <form action="" method="POST">
            <div class="form-group">
                <label>Nomor Handphone Aktif</label>
                <input type="tel" name="no_telp" placeholder="Contoh: 0895370086xx" required <?php echo empty($riwayat_item) ? 'disabled' : ''; ?>>
            </div>

            <div class="form-group">
                <label>Alamat Lengkap Pengiriman</label>
                <textarea name="alamat" placeholder="Nama Jalan, RT/RW, Kecamatan, Kota, Kode Pos" required <?php echo empty($riwayat_item) ? 'disabled' : ''; ?>></textarea>
            </div>

            <div class="form-group">
                <label>Pilih Jasa Pengiriman (Kurir)</label>
                <select name="jasa_kirim" required <?php echo empty($riwayat_item) ? 'disabled' : ''; ?>>
                    <option value="" disabled selected>-- Pilih Opsi Kurir --</option>
                    <option value="Reguler (Instant Delivery)">Reguler (Instant Delivery) - Rp. 15.000</option>
                    <option value="Same Day Service">Same Day Service - Rp. 25.000</option>
                    <option value="PetCareHub Cargo Express">PetCareHub Cargo Express - Rp. 40.000</option>
                </select>
            </div>

            <div class="form-group">
                <label>Catatan Tambahan Untuk Penjual (Opsional)</label>
                <input type="text" name="catatan" placeholder="Contoh: Tolong bungkus bubble wrap tebal ya" <?php echo empty($riwayat_item) ? 'disabled' : ''; ?>>
            </div>

            <button type="submit" name="proses_pembayaran" class="btn-complete" <?php echo empty($riwayat_item) ? 'disabled' : ''; ?>>Selesaikan Pembelian</button>
        </form>

        <a href="produk.php" class="back-link">&larr; Input Transaksi Baru</a>
    </div>

</body>
</html>