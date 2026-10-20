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
include "bagiankode/head.php"; 

// Logika Hapus Antrean Dokter (Batal)
if (isset($_GET['hapus_nota'])) {
    $nota_hapus = $conn->real_escape_string($_GET['hapus_nota']);
    mysqli_query($conn, "DELETE FROM booking_konsultasi WHERE id_booking = '$nota_hapus'");
    header("Location: keloladokter.php");
    exit();
}
?>

<style>
    .btn-xs {
        padding: 0.25rem 0.4rem;
        font-size: 0.75rem;
        border-radius: 0.2rem;
    }
    .btn-xs i {
        font-size: 0.75rem;
    }
</style>

<body class="sb-nav-fixed">
    <?php include "bagiankode/menunav.php"; ?>
    <div id="layoutSidenav">
        <?php include "bagiankode/menu.php"; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Kelola Booking Dokter</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Booking Konsultasi</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header bg-danger text-white">
                            <i class="bi bi-telephone-plus-fill me-1"></i> Daftar Antrean Konsultasi Medis
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered table-hover font-size-sm" style="font-size: 11pt;">
                                <thead class="table-dark">
                                    <tr style="text-align: center;">
                                        <th style="width: 5%;">No.</th>
                                        <th>Username</th>
                                        <th>Paket Konsul</th>
                                        <th>Biaya Jasa</th>
                                        <th>Nama Hewan</th>
                                        <th>Jadwal Konsultasi</th>
                                        <th style="width: 12%;">Aksi</th> </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query = mysqli_query($conn, "SELECT * FROM booking_konsultasi ORDER BY tanggal_konsul DESC, jam_konsul DESC");
                                    
                                    if ($query && mysqli_num_rows($query) > 0) {
                                        while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $no++; ?>.</td>
                                            <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($row['paket_konsul']); ?></td>
                                            <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_hewan']); ?></td>
                                            <td><strong><?php echo date('d M Y', strtotime($row['tanggal_konsul'])); ?></strong> pada <?php echo htmlspecialchars($row['jam_konsul']); ?> WIB</td>
                                            <td style="text-align: center; white-space: nowrap;">
                                                <a href="selesaidokter.php?id=<?php echo $row['id_booking']; ?>" class="btn btn-xs btn-success me-1" onclick="return confirm('Apakah sesi konsultasi dokter untuk anabul ini sudah selesai?');" title="Selesai">
                                                    <i class="bi bi-check-circle-fill"></i> Selesai
                                                </a>
                                                
                                                <a href="keloladokter.php?hapus_nota=<?php echo urlencode($row['id_booking']); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Batalkan jadwal konsultasi ini?');" title="Batal">
                                                    <i class="bi bi-trash-fill"></i> Batal
                                                </a>
                                            </td>
                                        </tr>
                                    <?php 
                                        } 
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center text-muted py-3'>Belum ada booking konsultasi dokter.</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
            <?php include "bagiankode/footer.php"; ?>
        </div>
    </div>
    <?php include "bagiankode/jsscript.php" ?>
</body>
</html>