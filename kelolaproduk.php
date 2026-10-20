<?php 
ob_start();
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include("includes/config.php");

if (isset($_POST["Simpan"])) {
    $nama_produk  = $conn->real_escape_string($_POST['namaProduk']);
    $kategori     = $conn->real_escape_string($_POST['kategoriProduk']);
    $harga        = floatval($_POST['hargaProduk']);
    $deskripsi         = intval($_POST['deskProduk']);

    if (!empty($nama_produk) && $harga > 0) {
        $sql_insert = "INSERT INTO produk (nama_produk, kategori, harga, deskripsi) 
                       VALUES ('$nama_produk', '$kategori', '$harga', '$deskripsi')";
        
        if ($conn->query($sql_insert) === TRUE) {
            header("location:kelolaproduk.php");
            exit();
        } else {
            $error_msg = "Gagal menyimpan data produk: " . $conn->error;
        }
    } else {
        $warning_msg = "Nama produk dan harga wajib diisi dengan benar!";
    }
}

$query_produk = mysqli_query($conn, "SELECT * FROM produk ORDER BY id DESC");
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
                    <h1 class="mt-4">Kelola Data Produk</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Produk / Inventori Toko</li>
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
                                            placeholder="Isikan Nama Produk / Makanan / Aksesoris" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="kategoriProduk" class="col-sm-2 col-form-label">Kategori</label>
                                    <div class="col-sm-10">
                                        <select class="form-select" id="kategoriProduk" name="kategoriProduk" required>
                                            <option value="" disabled selected>Pilih Kategori Produk</option>
                                            <option value="food">Makanan Kucing & Anjing</option>
                                            <option value="grooming">Kebutuhan Grooming</option>
                                            <option value="toys">Aksesoris & Mainan</option>
                                            <option value="health">Obat & Vitamin</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="hargaProduk" class="col-sm-2 col-form-label">Harga Jual</label>
                                    <div class="col-sm-10">
                                        <input type="number" class="form-control" id="hargaProduk" name="hargaProduk"
                                            placeholder="Contoh: 50000" min="0" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="deskProduk" class="col-sm-2 col-form-label">Deskripsi produk</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="deskProduk" name="deskProduk"
                                            placeholder="Contoh: 15" min="0" required>
                                    </div>
                                </div>
                                <div class="row mb-3 mt-4">
                                    <div class="col-sm-2"></div>
                                    <div class="col-sm-10">
                                        <input type="submit" class="btn btn-success px-4" value="Simpan" name="Simpan">
                                        <input type="reset" class="btn btn-danger px-4" value="Batal" name="Batal">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-1"></div>
                    </div>

                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <div class="jumbotron mt-5 mb-3">
                                <h2 class="display-6" style="font-size: 20pt; font-weight: 600;">Daftar Produk PetShop</h2>
                            </div>

                            <table class="table table-striped table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="text-align: center; width: 5%;">No.</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Harga</th>
                                        <th style="width: 30%; text-align: center;">Deskripsi Produk</th>
                                        <th colspan="2" style="text-align:center; width: 15%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    if ($query_produk && mysqli_num_rows($query_produk) > 0) {
                                        while ($row = mysqli_fetch_array($query_produk)) { 
                                    ?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo $no++; ?>.</td>
                                        <td><strong><?php echo htmlspecialchars($row['nama_produk']); ?></strong></td>
                                        <td><span class="badge bg-secondary"><?php echo htmlspecialchars($row['kategori']); ?></span></td>
                                        <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                        <td style="text-align: center;"><?php echo htmlspecialchars($row['deskripsi']); ?></td>
                                        
                                        <td style="text-align: center;">
                                            <a href="editproduk.php?id=<?php echo $row["id"]; ?>"
                                                class="btn btn-sm btn-success" title="EDIT">
                                                <i class="bi bi-pencil-square"></i>
                                            </a>
                                        </td>
                                        
                                        <td style="text-align: center;">
                                            <a href="hapusproduk.php?id=<?php echo $row["id"]; ?>"
                                                class="btn btn-sm btn-danger" title="HAPUS"
                                                onclick="return confirm('Apakah Anda yakin ingin menghapus data produk ini?');">
                                                <i class="bi bi-trash"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    <?php 
                                        }
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center text-muted py-3'>Belum ada data produk di database.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
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