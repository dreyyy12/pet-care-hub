<?php 
ob_start();
session_start();

// Proteksi halaman admin: Pastikan user admin sudah login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

// Hubungkan ke file konfigurasi database milikmu
include("includes/config.php");

// Pengecekan parameter ID di URL
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_produk = intval($_GET['id']);
    
    // Ambil data produk lama berdasarkan ID
    $query_ambil = $conn->query("SELECT * FROM produk WHERE id = $id_produk");
    if ($query_ambil && $query_ambil->num_rows > 0) {
        $data = $query_ambil->fetch_assoc();
    } else {
        header("Location: kelolaproduk.php");
        exit();
    }
} else {
    header("Location: kelolaproduk.php");
    exit();
}

// ====================================================================
// LOGIKA PROSES UPDATE DATA PRODUK (SAAT TOMBOL UPDATE DIKLIK)
// ====================================================================
if (isset($_POST["Update"])) {
    $nama_produk  = $conn->real_escape_string($_POST['namaProduk']);
    $kategori     = $conn->real_escape_string($_POST['kategoriProduk']);
    $harga        = floatval($_POST['hargaProduk']);
    $deskripsi    = $conn->real_escape_string($_POST['deskProduk']); // Tetap amankan teks input

    if (!empty($nama_produk) && $harga > 0) {
        // Jalankan perintah UPDATE data ke tabel produk
        $sql_update = "UPDATE produk SET 
                        nama_produk = '$nama_produk', 
                        kategori = '$kategori', 
                        harga = '$harga', 
                        deskripsi = '$deskripsi' 
                       WHERE id = $id_produk";
        
        if ($conn->query($sql_update) === TRUE) {
            header("Location: kelolaproduk.php");
            exit();
        } else {
            $error_msg = "Gagal memperbarui data produk: " . $conn->error;
        }
    } else {
        $warning_msg = "Nama produk dan harga wajib diisi dengan benar!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<?php include "bagiankode/head.php"; ?>

<body class="sb-nav-fixed">
    <?php include "bagiankode/menunav.php"; ?>
    <div id="layoutSidenav">
        <?php include "bagiankode/menu.php"; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Edit Data Produk</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="kelolaproduk.php" class="text-decoration-none">Kelola Produk</a></li>
                        <li class="breadcrumb-item active">Ubah Info Produk</li>
                    </ol>

                    <?php if (isset($error_msg)): ?>
                        <div class='alert alert-danger'><?php echo $error_msg; ?></div>
                    <?php endif; ?>
                    <?php if (isset($warning_msg)): ?>
                        <div class='alert alert-warning'><?php echo $warning_msg; ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <form method="POST">
                                <div class="row mb-3 mt-4">
                                    <label for="namaProduk" class="col-sm-2 col-form-label">Nama Produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="namaProduk" name="namaProduk"
                                            value="<?php echo htmlspecialchars($data['nama_produk']); ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="kategoriProduk" class="col-sm-2 col-form-label">Kategori</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" id="kategoriProduk" name="kategoriProduk" required>
                                            <option value="food" <?php echo ($data['kategori'] == 'food') ? 'selected' : ''; ?>>Makanan Kucing & Anjing</option>
                                            <option value="grooming" <?php echo ($data['kategori'] == 'grooming') ? 'selected' : ''; ?>>Kebutuhan Grooming</option>
                                            <option value="toys" <?php echo ($data['kategori'] == 'toys') ? 'selected' : ''; ?>>Aksesoris & Mainan</option>
                                            <option value="health" <?php echo ($data['kategori'] == 'health') ? 'selected' : ''; ?>>Obat & Vitamin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="hargaProduk" class="col-sm-2 col-form-label">Harga Jual</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="hargaProduk" name="hargaProduk"
                                            value="<?php echo $data['harga']; ?>" min="0" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="deskProduk" class="col-sm-2 col-form-label">Deskripsi produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="deskProduk" name="deskProduk"
                                            value="<?php echo htmlspecialchars($data['deskripsi']); ?>" required>
                                    </div>
                                </div>
                                <div class="row mb-3 mt-4">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <input type="submit" class="btn btn-success px-4" value="Simpan Perubahan" name="Update">
                                        <a href="kelolaproduk.php" class="btn btn-danger px-4">Kembali</a>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-1"></div>
                    </div>

                </div>
            </main>
            <?php include "bagiankode/footer.php"; ?>
        </div>
    </div>
    <?php include "bagiankode/jsscript.php" ?>
</body>
</html>