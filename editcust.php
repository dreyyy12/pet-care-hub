<?php 
ob_start();
session_start();

// Proteksi halaman admin: Pastikan user sudah login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include "includes/config.php";
include "bagiankode/head.php";
?>

<body class="sb-nav-fixed">
<?php include "bagiankode/menunav.php"; ?>
<div id="layoutSidenav">
    <?php include "bagiankode/menu.php"; ?>
    <div id="layoutSidenav_content">
        <main>
            <div class="container-fluid px-4">
                <h1 class="mt-4">Edit Data Customer</h1>
                <ol class="breadcrumb mb-4">
                    <li class="breadcrumb-item active">Edit Data Customer / Akun</li>
                </ol>

<?php
// Ambil parameter email lama dari URL
if (!isset($_GET["ubahemail"]) || empty($_GET["ubahemail"])) {
    die("<div class='alert alert-danger'>Data Email Customer tidak ditemukan di URL.</div>");
}

$email_lama = $_GET["ubahemail"];

// Ambil data customer dari database berdasarkan email lama
$edit = mysqli_query($conn, "SELECT * FROM users WHERE email='$email_lama'");
$row_edit = mysqli_fetch_array($edit);

// Jika data yang mau diedit tidak ditemukan di database
if (!$row_edit) {
    echo "<div class='alert alert-danger'>Data customer dengan email tersebut tidak ditemukan di database!</div>";
}

// Jika tombol "Ubah" ditekan, proses update semua data ke database
if (isset($_POST['Ubah'])) {
    $email_key_lama = $_POST['emailKeyLama']; 
    $email_baru     = $conn->real_escape_string(trim($_POST['emailUser'])); 
    $nama_lengkap   = $conn->real_escape_string(trim($_POST['namaLengkap']));
    $no_telp        = $conn->real_escape_string(trim($_POST['noTelp']));
    $password_baru  = $conn->real_escape_string($_POST['passUser']); // Mengamankan input password baru

    // Cek jika admin mengubah email, pastikan email baru tersebut tidak duplikat
    if ($email_baru !== $email_key_lama) {
        $check_duplicate = $conn->query("SELECT * FROM users WHERE email = '$email_baru'");
        if ($check_duplicate && $check_duplicate->num_rows > 0) {
            echo "<div class='alert alert-danger'>Gagal Update: Email baru '" . htmlspecialchars($email_baru) . "' sudah digunakan oleh akun lain!</div>";
            // Reload data lama agar form tidak kosong
            $edit = mysqli_query($conn, "SELECT * FROM users WHERE email='$email_key_lama'");
            $row_edit = mysqli_fetch_array($edit);
            goto skip_update;
        }
    }

    // Jalankan Query UPDATE (Kolom password dipastikan ikut ter-update dengan aman)
    $sql_update = "UPDATE users SET 
        email='$email_baru',
        nama_lengkap='$nama_lengkap',
        no_telp='$no_telp',
        password='$password_baru'
        WHERE email='$email_key_lama'";

    if (mysqli_query($conn, $sql_update)) {
        // Kembali ke halaman utama setelah sukses
        header("Location: inputcust.php");
        exit;
    } else {
        // Menampilkan pesan error spesifik dari MySQL jika gagal
        echo "<div class='alert alert-danger'>Gagal memperbarui database: " . mysqli_error($conn) . "</div>";
    }
}

skip_update: 
// Ambil daftar user terbaru untuk tabel di bawah form
$query_customer = mysqli_query($conn, "SELECT * FROM users ORDER BY email ASC");
?>

<div class="row">
    <div class="col-1"></div>
    <div class="col-10">

        <form method="post">
            <input type="hidden" name="emailKeyLama" value="<?php echo htmlspecialchars($row_edit['email'] ?? ''); ?>">

            <div class="row mb-3 mt-4">
                <label for="emailUser" class="col-sm-2 col-form-label">Alamat Email</label>
                <div class="col-sm-10">
                    <input type="email" class="form-control" id="emailUser" name="emailUser" 
                        value="<?php echo htmlspecialchars($row_edit['email'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="namaLengkap" class="col-sm-2 col-form-label">Nama Lengkap</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="namaLengkap" name="namaLengkap" 
                        value="<?php echo htmlspecialchars($row_edit['nama_lengkap'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="noTelp" class="col-sm-2 col-form-label">No. Telepon / HP</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="noTelp" name="noTelp" 
                        value="<?php echo htmlspecialchars($row_edit['no_telp'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="row mb-3">
                <label for="passUser" class="col-sm-2 col-form-label">Password Baru</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="passUser" name="passUser"
                        value="<?php echo htmlspecialchars($row_edit['password'] ?? ''); ?>" required>
                </div>
            </div>

            <div class="form-group row">
                <div class="col-2"></div>
                <div class="col-10">
                    <input type="submit" class="btn btn-success px-4" value="Ubah" name="Ubah">
                    <a href="inputcust.php" class="btn btn-secondary px-4">Batal</a>
                </div>
            </div>
        </form>

        <div class="jumbotron mt-5 mb-3">
            <h2 class="display-6" style="font-size: 20pt; font-weight: 600;">Daftar Akun Customer</h2>
        </div>

        <table class="table table-striped table-bordered table-hover mt-4">
            <thead class="table-dark">
                <tr>
                    <th style="text-align: center; width: 5%;">No.</th>
                    <th>Nama Lengkap</th>
                    <th>No. Telepon</th>
                    <th>Email</th>
                    <th>Password</th>
                    <th colspan="2" style="text-align: center; width: 15%;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if ($query_customer) {
                    while ($row = mysqli_fetch_array($query_customer)) { 
                    ?>
                    <tr>
                        <td style="text-align: center;"><?php echo $no++; ?>.</td>
                        <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                        <td><?php echo htmlspecialchars($row['no_telp']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><code><?php echo htmlspecialchars($row['password']); ?></code></td>
                        <td style="text-align: center;">
                            <a href="editcust.php?ubahemail=<?php echo urlencode($row['email']); ?>" class="btn btn-sm btn-success" title="EDIT">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                        <td style="text-align: center;">
                            <a href="hapuscust.php?hapusemail=<?php echo urlencode($row['email']); ?>" class="btn btn-sm btn-danger" title="HAPUS" onclick="return confirm('Yakin ingin menghapus data ini?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php 
                    }
                } 
                ?>
            </tbody>
        </table>

    </div>
</div>

        </main>
        <?php include "bagiankode/footer.php"; ?>
    </div>
</div>
<?php include "bagiankode/jsscript.php"; ?>
</body>
</html>