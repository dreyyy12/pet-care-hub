<?php 
ob_start();
session_start();

// Proteksi halaman admin: Pastikan user sudah login
if (!isset($_SESSION["username"])) {
    header("location:login.php");
    exit();
}
?>

<?php include "bagiankode/head.php"; ?>

<body class="sb-nav-fixed">
    <?php include "bagiankode/menunav.php"; ?>
    <div id="layoutSidenav">
        <?php include "bagiankode/menu.php"; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Input Data Customer</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item active">Customer / Pasien</li>
                    </ol>

                    <?php
                    // Hubungkan ke file konfigurasi database milikmu
                    include("includes/config.php");

                    // LOGIKA PROSES TAMBAH CUSTOMER BARU (INSERT)
                    if (isset($_POST["Simpan"])) {
                        $nama_lengkap = $conn->real_escape_string($_POST['namaLengkap']);
                        $no_telp      = $conn->real_escape_string($_POST['noTelp']);
                        $email        = $conn->real_escape_string($_POST['emailUser']);
                        $password     = $_POST['passUser']; // Ambil plain password atau bisa pakai password_hash jika diperlukan

                        if (!empty($email) && !empty($nama_lengkap)) {
                            // Cek apakah email sudah terdaftar sebelumnya
                            $check_email = $conn->query("SELECT * FROM users WHERE email = '$email'");
                            if ($check_email && $check_email->num_rows > 0) {
                                echo "<div class='alert alert-danger'>Gagal: Email ini sudah terdaftar di sistem!</div>";
                            } else {
                                // Jalankan perintah INSERT ke tabel database users milikmu
                                $sql_insert = "INSERT INTO users (nama_lengkap, no_telp, email, password) 
                                               VALUES ('$nama_lengkap', '$no_telp', '$email', '$password')";
                                
                                if ($conn->query($sql_insert) === TRUE) {
                                    header("location:inputcust.php");
                                    exit();
                                } else {
                                    echo "<div class='alert alert-danger'>Gagal menyimpan data: " . $conn->error . "</div>";
                                }
                            }
                        } else {
                            echo "<div class='alert alert-warning'>Semua bidang input wajib diisi dengan benar!</div>";
                        }
                    }

                    // Ambil daftar seluruh customer terdaftar dari database
                    $query_customer = mysqli_query($conn, "SELECT * FROM users ORDER BY email ASC");
                    ?>

                    <div class="row">
                        <div class="col-1"></div>
                        <div class="col-10">
                            <form method="POST">
                                <div class="row mb-3 mt-4">
                                    <label for="namaLengkap" class="col-sm-2 col-form-label">Nama Lengkap</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" id="namaLengkap" name="namaLengkap"
                                            placeholder="Isikan Nama Lengkap Customer" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="noTelp" class="col-sm-2 col-form-label">No. Telepon / HP</label>
                                    <div class="col-sm-10">
                                        <input type="tel" class="form-control" id="noTelp" name="noTelp"
                                            placeholder="Contoh: 08123456789" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="emailUser" class="col-sm-2 col-form-label">Alamat Email</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="emailUser" name="emailUser"
                                            placeholder="nama@email.com" required>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="passUser" class="col-sm-2 col-form-label">Password Akun</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="passUser" name="passUser"
                                            placeholder="Masukkan Password Akses Utama" required>
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
                                <h2 class="display-6" style="font-size: 20pt; font-weight: 600;">Daftar Akun Customer</h2>
                            </div>

                            <table class="table table-striped table-bordered table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th style="text-align: center; width: 5%;">No.</th>
                                        <th>Nama Lengkap</th>
                                        <th>No. Telepon</th>
                                        <th>Email</th>
                                        <th>Password</th>
                                        <th colspan="2" style="text-align:center; width: 15%;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $no = 1;
                                    while ($row = mysqli_fetch_array($query_customer)) { 
                                    ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $no++; ?>.</td>
                                            <td><?php echo htmlspecialchars($row['nama_lengkap']); ?></td>
                                            <td><?php echo htmlspecialchars($row['no_telp']); ?></td>
                                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                                            <td><code><?php echo htmlspecialchars($row['password']); ?></code></td>
                                            <td style="text-align: center;">
                                                <a href="editcust.php?ubahemail=<?php echo urlencode($row["email"]); ?>"
                                                    class="btn btn-sm btn-success" title="EDIT">
                                                    <i class="bi bi-pencil-square"></i>
                                                </a>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="hapuscust.php?hapusemail=<?php echo urlencode($row["email"]); ?>"
                                                    class="btn btn-sm btn-danger" title="HAPUS" 
                                                    onclick="return confirm('Apakah Anda yakin ingin menghapus data customer ini?');">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php } ?>
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