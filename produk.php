<?php
session_start();
date_default_timezone_set('Asia/Jakarta');

if (!isset($_SESSION['username'])) {
    $halaman_ini = basename($_SERVER['PHP_SELF']); 
    
    header("Location: login.php?redirect=" . $halaman_ini);
    exit();
}

if (!isset($_SESSION['no_nota'])) {
    $_SESSION['no_nota'] = time() . "_" . rand(1000, 9999);
}

$host = "localhost";
$user = "root";
$pass = "";
$db   = "softdev";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi ke MySQL XAMPP Gagal: " . $conn->connect_error);
}

// LOGIKA PROSES KERANJANG DIJALANKAN DI SINI SEBELUM HALAMAN DIALIKAN
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tambah_keranjang'])) {
    
    $nama_produk = $conn->real_escape_string($_POST['nama_produk']);
    $harga       = floatval($_POST['harga']);
    $qty         = isset($_POST['qty']) ? intval($_POST['qty']) : 1;
    if ($qty < 1) { $qty = 1; }

    $total_harga = $harga * $qty;
    $no_nota_aktif = $_SESSION['no_nota'];

    // 1. AMANKAN DATA KE DATABASE (transaksi_produk)
    $check_column = $conn->query("SHOW COLUMNS FROM transaksi_produk LIKE 'no_nota'");
    if ($check_column && $check_column->num_rows > 0) {
        $sql = "INSERT INTO transaksi_produk (nama_produk, harga, qty, total_harga, no_nota) 
                VALUES ('$nama_produk', '$harga', '$qty', '$total_harga', '$no_nota_aktif')";
    } else {
        $sql = "INSERT INTO transaksi_produk (nama_produk, harga, qty, total_harga) 
                VALUES ('$nama_produk', '$harga', '$qty', '$total_harga')";
    }
    $conn->query($sql);

    // 2. AMANKAN DATA KE SESSION (Agar dibaca otomatis oleh kwitansi.php tanpa dummy)
    // Membuat ID Unik berbasis nama produk agar barang yang sama bisa terakumulasi QTY-nya
    $cart_id = md5($nama_produk); 
    
    if (isset($_SESSION['keranjang_belanja'][$cart_id])) {
        $_SESSION['keranjang_belanja'][$cart_id]['qty'] += $qty;
    } else {
        $_SESSION['keranjang_belanja'][$cart_id] = [
            'nama_produk' => $nama_produk,
            'harga'       => $harga,
            'qty'         => $qty,
            'kategori'    => 'Produk PetCareHub'
        ];
    }

    $conn->close();
    // Setelah data aman di Database & Session, lempar user ke output.php
    header("Location: output.php");
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
        justify-content: center;
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
        /* Berubah menjadi merah elegan khas PetCareHub saat di-hover */
        color: #ffffff;
    }

    .main-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .main-header h1 {
        font-size: 36pt;
        color: #fff;
        font-family: Georgia, serif;
    }

    .main-header h1 span {
        color: #e5c185;
    }

    .product-category-nav ul {
        display: flex;
        list-style: none;
        gap: 35px;
        padding: 0;
        margin: 0 0 40px 0;
        flex-wrap: wrap;
        justify-content: center;
    }

    .product-category-nav ul li {
        font-size: 13px;
        font-weight: 500;
        letter-spacing: 2px;
        cursor: pointer;
        transition: all 0.3s ease;
        text-transform: uppercase;
        padding-bottom: 6px;
        border-bottom: 2px solid transparent;
    }

    .product-category-nav ul li:hover,
    .product-category-nav ul li.active {
        border-bottom: 2px solid #6d5b43;
    }

    .product-category-nav ul li a {
        color: #a3a3a1;
        text-decoration: none;
        display: inline-block;
    }

    .product-category-nav ul li.active a {
        color: #ffffff;
        font-weight: 600;
    }

    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 20px;
        width: 100%;
    }

    .product-card {
        background: #000;
        border: 1px solid #1c1c19;
        border-radius: 8px;
        padding: 15px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        transition: border-color 0.3s;
    }

    .product-card:hover {
        border-color: #e5c185;
    }

    .product-img-placeholder {
        width: 100%;
        height: 280px;
        background: #141412;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        border: 1px solid #1c1c19;
    }

    .product-img-placeholder img {
        max-width: 100%;
        max-height: 100%;
        width: auto;
        height: auto;
        object-fit: contain;
    }

    .product-info h3 {
        font-size: 13pt;
        font-weight: 600;
        margin-bottom: 10px;
        color: #fff;
    }

    .product-info p {
        font-size: 10pt;
        color: #888;
        margin-bottom: 15px;
        line-height: 1.5;
    }

    .price-qty-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .product-info .price {
        color: #e5c185;
        font-weight: bold;
        font-size: 14pt;
    }

    .qty-wrapper {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 10pt;
        color: #a3a3a1;
    }

    .qty-input-box {
        width: 55px;
        background: #1c1c19;
        border: 1px solid #2c2c28;
        color: #fff;
        padding: 6px;
        border-radius: 4px;
        text-align: center;
    }

    .add-to-cart-btn {
        width: 100%;
        background: #1c1c19;
        border: 1px solid #2c2c28;
        color: #a3a3a1;
        padding: 12px;
        border-radius: 6px;
        cursor: pointer;
        font-weight: bold;
        font-size: 9pt;
        text-transform: uppercase;
    }

    .add-to-cart-btn:hover {
        background: #e5c185;
        color: #141412;
        border-color: #e5c185;
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
                <li class="active"><a href="produk.php">PRODUCT</a></li>
                <li><a href="kontak.php">CONTACT</a></li>
            </ul>
            <a href="logout.php" class="btn-logout"
                onclick="return confirm('Apakah Anda yakin ingin keluar dari akun?');">LOGOUT</a>
        </nav>

        <header class="main-header">
            <h1>PetCareHub <span>Premium Product Service</span></h1>
        </header>

        <nav class="product-category-nav">
            <ul>
                <li class="<?php echo (!isset($_GET['kategori']) || $_GET['kategori'] == 'all') ? 'active' : ''; ?>">
                    <a href="produk.php?kategori=all">ALL PRODUCTS</a>
                </li>
                <li class="<?php echo (isset($_GET['kategori']) && $_GET['kategori'] == 'food') ? 'active' : ''; ?>">
                    <a href="produk.php?kategori=food">FOOD & NUTRITION</a>
                </li>
                <li
                    class="<?php echo (isset($_GET['kategori']) && $_GET['kategori'] == 'grooming') ? 'active' : ''; ?>">
                    <a href="produk.php?kategori=grooming">GROOMING SUPPLIES</a>
                </li>
                <li class="<?php echo (isset($_GET['kategori']) && $_GET['kategori'] == 'toys') ? 'active' : ''; ?>">
                    <a href="produk.php?kategori=toys">TOYS & ACCESSORIES</a>
                </li>
                <li class="<?php echo (isset($_GET['kategori']) && $_GET['kategori'] == 'health') ? 'active' : ''; ?>">
                    <a href="produk.php?kategori=health">HEALTH & WELLNESS</a>
                </li>
            </ul>
        </nav>

        <div class="product-grid">
            <?php
            $kategori_aktif = isset($_GET['kategori']) ? $_GET['kategori'] : 'all';
            if ($kategori_aktif == 'all') {
                $sql_produk = "SELECT * FROM produk";
            } else {
                $kategori_aman = $conn->real_escape_string($kategori_aktif);
                $sql_produk = "SELECT * FROM produk WHERE kategori = '$kategori_aman'";
            }

            $result_produk = $conn->query($sql_produk);

            if ($result_produk && $result_produk->num_rows > 0) {
                while ($row = $result_produk->fetch_assoc()) {
                    ?>
            <div class="product-card">
                <form action="" method="POST">
                    <div class="product-img-placeholder">
                        <img src="img/<?php echo $row['gambar']; ?>">
                    </div>
                    <div class="product-info">
                        <h3><?php echo htmlspecialchars($row['nama_produk']); ?></h3>
                        <p><?php echo htmlspecialchars($row['deskripsi']); ?></p>
                        <div class="price-qty-row">
                            <span class="price">Rp.<?php echo number_format($row['harga'], 0, ',', '.'); ?></span>
                            <div class="qty-wrapper">
                                <label>QTY</label>
                                <input type="number" name="qty" class="qty-input-box" value="1" min="1" required>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" name="nama_produk"
                        value="<?php echo htmlspecialchars($row['nama_produk']); ?>">
                    <input type="hidden" name="harga" value="<?php echo $row['harga']; ?>">

                    <div class="card-actions">
                        <button type="submit" name="tambah_keranjang" class="add-to-cart-btn">Tambahkan Ke
                            Keranjang</button>
                    </div>
                </form>
            </div>
            <?php
                }
            } else {
                echo "<p style='color: #888; text-align: center; width: 100%; margin-top: 20px;'>Belum ada produk untuk kategori ini.</p>";
            }
            ?>
        </div>
    </div>

</body>

</html>
<?php $conn->close(); ?>