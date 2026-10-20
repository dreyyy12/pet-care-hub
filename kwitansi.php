<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

if (isset($_GET['action']) && $_GET['action'] === 'clear_cart') {

    unset($_SESSION['keranjang_belanja']);
    unset($_SESSION['jenis_transaksi']);
    unset($_SESSION['nama_layanan']);
    unset($_SESSION['detail_tambahan']);
    unset($_SESSION['grand_total']);
    unset($_SESSION['alamat']);
    unset($_SESSION['no_telp']);
    unset($_SESSION['catatan']);
    unset($_SESSION['jasa_kirim']);
    unset($_SESSION['metode_pembayaran']);
    
    $_SESSION['no_nota'] = time() . "_" . rand(1000, 9999);
    
    header("Location: home.php");
    exit();
}

if (!isset($_SESSION['username']) || !isset($_SESSION['no_nota'])) {
    header("Location: produk.php");
    exit();
}

// 1. Mengambil metadata dasar dari Session
$nama_pembeli       = $_SESSION['username'];
$no_pesanan         = $_SESSION['no_nota'];
$tgl_transaksi      = date('d/m/y');
$metode_pembayaran   = isset($_SESSION['metode_pembayaran']) ? $_SESSION['metode_pembayaran'] : "QRIS";

// Cek apakah jenis transaksinya Jasa/Layanan (Grooming, Hotel, Konsul)
$is_layanan = (isset($_SESSION['jenis_transaksi']) && $_SESSION['jenis_transaksi'] !== 'produk');

// 2. Inisialisasi Variabel Perhitungan Saldo Akhir
$subtotal_pesanan    = 0;
$subtotal_pengiriman = 0;
$biaya_layanan       = 0;
$diskon_pengiriman   = 0;
$jasa_kirim          = "-";
$alamat_pembeli      = "Akses Layanan (E-Tiket PetCareHub)";
$no_hp               = "-";

if ($is_layanan) {
    // KALKULASI REKAP JIKA BERUPA LAYANAN/SERVICES
    $subtotal_pesanan    = isset($_SESSION['grand_total']) ? $_SESSION['grand_total'] : 0;
    $subtotal_pengiriman = 0;
    $biaya_layanan       = 0;
    $diskon_pengiriman   = 0;
    $jasa_kirim          = "Booking Service Terjadwal";
} else {
    // KALKULASI REKAP JIKA BERUPA PRODUK FISIK (DATA DARI OUTPUT.PHP)
    $alamat_pembeli   = isset($_SESSION['alamat']) ? $_SESSION['alamat'] : "Alamat belum diisi";
    $no_hp            = isset($_SESSION['no_telp']) ? $_SESSION['no_telp'] : "-";
    $jasa_kirim       = isset($_SESSION['jasa_kirim']) ? $_SESSION['jasa_kirim'] : "Reguler (Instant Delivery)";
    
    // Tarik daftar barang belanjaan dari session
    $items = [];
    if (isset($_SESSION['keranjang_belanja']) && !empty($_SESSION['keranjang_belanja'])) {
        $no = 1;
        foreach ($_SESSION['keranjang_belanja'] as $id_produk => $item) {
            $subtotal_barang = $item['harga'] * $item['qty'];
            $subtotal_pesanan += $subtotal_barang;
            
            $items[] = [
                "no" => $no++,
                "nama" => $item['nama_produk'],
                "sub_nama" => isset($item['kategori']) ? $item['kategori'] : "Produk PetCareHub",
                "harga" => $item['harga'],
                "qty" => $item['qty'],
                "subtotal" => $subtotal_barang
            ];
        }
    }

    // Set Ongkir dinamis produk berdasarkan kurir yang dipilih di output.php
    if ($subtotal_pesanan > 0) {
        if (strpos($jasa_kirim, 'Same Day') !== false) {
            $subtotal_pengiriman = 25000;
        } elseif (strpos($jasa_kirim, 'Cargo') !== false) {
            $subtotal_pengiriman = 40000;
        } else {
            $subtotal_pengiriman = 15000;
        }
        $biaya_layanan     = 2000;
        $diskon_pengiriman = ($subtotal_pesanan > 500000) ? 15000 : 0;
    }
}

// Total bayar final setelah kalkulasi rumus di atas
$total_pembayaran = ($subtotal_pesanan + $subtotal_pengiriman + $biaya_layanan) - $diskon_pengiriman;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Nota Pesanan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background-color: #000000;
            padding: 50px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .invoice-card {
            background-color: #ffffff;
            color: #111111;
            width: 100%;
            max-width: 800px;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(229, 193, 133, 0.15);
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .invoice-header {
            text-align: center;
            margin-bottom: 35px;
        }

        .brand-logo {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 36px;
            font-weight: 700;
            color: #000000;
        }

        .brand-logo span {
            color: #bfa37a;
        }

        .invoice-header h2 {
            font-size: 16px;
            color: #888888;
            letter-spacing: 3px;
            text-transform: uppercase;
            margin-top: 5px;
            font-weight: 600;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px 40px;
            font-size: 14px;
            margin-bottom: 30px;
            line-height: 1.5;
        }

        .info-row {
            display: flex;
            align-items: flex-start;
        }

        .info-row .label {
            width: 140px;
            font-weight: 600;
            color: #333333;
            flex-shrink: 0;
        }

        .info-row .value {
            color: #111111;
        }

        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 35px;
            font-size: 13px;
        }

        .meta-table th {
            background-color: #f2f2f2;
            color: #111111;
            font-weight: 600;
            padding: 12px 10px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }

        .meta-table td {
            padding: 12px 10px;
            border: 1px solid #e0e0e0;
            text-align: center;
            color: #111111;
            font-weight: 500;
        }

        .section-title {
            font-size: 16px;
            font-weight: 700;
            margin-bottom: 12px;
            color: #000000;
            letter-spacing: 0.5px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
            margin-bottom: 20px;
        }

        .items-table th {
            background-color: #f2f2f2;
            color: #111111;
            font-weight: 600;
            padding: 12px 10px;
            border-top: 1px solid #cccccc;
            border-bottom: 1px solid #cccccc;
        }

        .items-table td {
            padding: 16px 10px;
            border-bottom: 1px solid #eeeeee;
            vertical-align: top;
        }

        .product-name {
            font-weight: 700;
            color: #111111;
        }

        .product-sub {
            font-size: 11px;
            color: #666666;
            margin-top: 2px;
        }

        .calculation-container {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            width: 100%;
            font-size: 13px;
            gap: 10px;
            margin-bottom: 35px;
        }

        .calc-row {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 350px;
        }

        .calc-row .label {
            color: #555555;
        }

        .calc-row .value {
            font-weight: 500;
            color: #111111;
            text-align: right;
        }

        .calc-row.final-total {
            border-top: 1px solid #111111;
            padding-top: 12px;
            margin-top: 5px;
            font-size: 15px;
        }

        .calc-row.final-total .label {
            font-weight: 700;
            color: #000000;
        }

        .calc-row.final-total .value {
            font-weight: 700;
            color: #000000;
        }

        .invoice-footer {
            border-top: 1px solid #eeeeee;
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999999;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        .home-action-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .btn-home {
            display: inline-block;
            background-color: #141412;
            color: #e5c185;
            text-decoration: none;
            padding: 14px 45px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            border: 1px solid #33291c;
            text-align: center;
            transition: all 0.25s ease;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
        }

        .btn-home:hover {
            background-color: #e5c185;
            color: #000000;
            border-color: #e5c185;
            box-shadow: 0 4px 20px rgba(229, 193, 133, 0.4);
        }
    </style>
</head>
<body>

    <div class="invoice-card">
        
        <div class="invoice-header">
            <div class="brand-logo">Pet Care <span>Hub</span></div>
            <h2>Nota Pesanan</h2>
        </div>

        <div class="info-grid">
            <div class="info-row">
                <div class="label">Nama Pembeli:</div>
                <div class="value"><?php echo htmlspecialchars($nama_pembeli); ?></div>
            </div>
            <div class="info-row">
                <div class="label">Nama Penjual:</div>
                <div class="value">Pet Care Hub Official</div>
            </div>
            
            <?php if (!$is_layanan): ?>
            <div class="info-row" style="grid-column: span 2;">
                <div class="label">Alamat Pembeli:</div>
                <div class="value"><?php echo htmlspecialchars($alamat_pembeli); ?></div>
            </div>
            <div class="info-row">
                <div class="label">No. Handphone:</div>
                <div class="value"><?php echo htmlspecialchars($no_hp); ?></div>
            </div>
            <?php else: ?>
            <div class="info-row" style="grid-column: span 2;">
                <div class="label">Keterangan:</div>
                <div class="value" style="color: #155724; font-weight: bold;">Layanan E-Tiket Terjadwal (Bawa hewan peliharaan Anda sesuai jadwal)</div>
            </div>
            <?php endif; ?>
        </div>

        <table class="meta-table">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Tanggal Transaksi</th>
                    <th>Metode Pembayaran</th>
                    <th>Keterangan Kirim / Paket</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo htmlspecialchars($no_pesanan); ?></td>
                    <td><?php echo htmlspecialchars($tgl_transaksi); ?></td>
                    <td><?php echo htmlspecialchars($metode_pembayaran); ?></td>
                    <td><?php echo htmlspecialchars($jasa_kirim); ?></td>
                </tr>
            </tbody>
        </table>

        <div class="section-title">Rincian Pesanan</div>
        
        <?php if ($is_layanan): ?>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 10%; text-align: center;">No.</th>
                        <th style="width: 60%; text-align: left; padding-left: 10px;">Jenis Paket Servis Layanan</th>
                        <th style="width: 30%; text-align: right; padding-right: 10px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="text-align: center;">1.</td>
                        <td>
                            <div class="product-name"><?php echo htmlspecialchars($_SESSION['nama_layanan']); ?></div>
                            <div class="product-sub"><?php echo htmlspecialchars($_SESSION['detail_tambahan']); ?></div>
                        </td>
                        <td style="text-align: right; padding-right: 10px; font-weight: 500;">Rp. <?php echo number_format($subtotal_pesanan, 0, ',', '.'); ?></td>
                    </tr>
                </tbody>
            </table>
        <?php else: ?>
            <table class="items-table">
                <thead>
                    <tr>
                        <th style="width: 5%; text-align: center;">No.</th>
                        <th style="width: 40%; text-align: left; padding-left: 10px;">Produk</th>
                        <th style="width: 25%; text-align: right;">Harga Produk</th>
                        <th style="width: 15%; text-align: center;">Kuantitas</th>
                        <th style="width: 15%; text-align: right; padding-right: 10px;">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($items)): ?>
                        <?php foreach ($items as $item): ?>
                        <tr>
                            <td style="text-align: center;"><?php echo $item['no']; ?>.</td>
                            <td>
                                <div class="product-name"><?php echo htmlspecialchars($item['nama']); ?></div>
                                <div class="product-sub"><?php echo htmlspecialchars($item['sub_nama']); ?></div>
                            </td>
                            <td style="text-align: right;">Rp. <?php echo number_format($item['harga'], 0, ',', '.'); ?></td>
                            <td style="text-align: center;"><?php echo $item['qty']; ?></td>
                            <td style="text-align: right; padding-right: 10px; font-weight: 500;">Rp. <?php echo number_format($item['subtotal'], 0, ',', '.'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" style="text-align: center; color: #888; padding: 20px;">Keranjang kosong atau transaksi tidak ditemukan.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <div class="calculation-container">
            <div class="calc-row">
                <div class="label">Subtotal Pesanan</div>
                <div class="value">Rp. <?php echo number_format($subtotal_pesanan, 0, ',', '.'); ?></div>
            </div>
            
            <?php if (!$is_layanan): ?>
            <div class="calc-row">
                <div class="label">Subtotal Pengiriman</div>
                <div class="value">Rp. <?php echo number_format($subtotal_pengiriman, 0, ',', '.'); ?></div>
            </div>
            <div class="calc-row">
                <div class="label">Biaya Layanan</div>
                <div class="value">Rp. <?php echo number_format($biaya_layanan, 0, ',', '.'); ?></div>
            </div>
            <div class="calc-row" style="color: #cc0000;">
                <div class="label">Diskon Pengiriman</div>
                <div class="value">- Rp. <?php echo number_format($diskon_pengiriman, 0, ',', '.'); ?></div>
            </div>
            <?php endif; ?>
            
            <div class="calc-row final-total">
                <div class="label">Total Pembayaran</div>
                <div class="value">Rp. <?php echo number_format($total_pembayaran, 0, ',', '.'); ?></div>
            </div>
        </div>

        <div class="invoice-footer">
            PT. Pet Care Hub Indonesia<br>
            Jl. Margonda Raya No. 123, Depok, Jawa Barat 16424
        </div>

        <div class="home-action-container">
            <a href="kwitansi.php?action=clear_cart" class="btn-home">Kembali ke Home</a>
        </div>

    </div>

</body>
</html>