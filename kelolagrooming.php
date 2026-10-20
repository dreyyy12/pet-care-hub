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
                    <h1 class="mt-4">Kelola Booking Grooming</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Booking Grooming</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header bg-info text-dark fw-bold">
                            <i class="bi bi-scissors me-1"></i> Daftar Antrean Salon / Grooming Hewan
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered table-hover font-size-sm" style="font-size: 11pt;">
                                <thead class="table-dark">
                                    <tr style="text-align: center;">
                                        <th style="width: 5%;">No.</th>
                                        <th>Username</th>
                                        <th>Paket Grooming</th>
                                        <th>Biaya Jasa</th>
                                        <th>Nama Hewan</th>
                                        <th>Jenis Hewan</th>
                                        <th>Jadwal Pengerjaan</th>
                                        <th style="width: 12%;">Aksi</th> </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // Mengurutkan berdasarkan tanggal checkin paling baru
                                    $query = mysqli_query($conn, "SELECT * FROM booking_grooming ORDER BY checkin DESC, checkin_jam DESC");
                                    
                                    if ($query && mysqli_num_rows($query) > 0) {
                                        while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $no++; ?>.</td>
                                            <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($row['layanan']); ?></td>
                                            <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_hewan']); ?></td>
                                            <td><?php echo htmlspecialchars($row['jenis_hewan']); ?></td>
                                            <td><strong><?php echo date('d M Y', strtotime($row['checkin'])); ?></strong> Jam <?php echo htmlspecialchars($row['checkin_jam']); ?> WIB</td>
                                            <td style="text-align: center; white-space: nowrap;">
                                                <a href="selesaigrooming.php?id=<?php echo $row['id']; ?>" class="btn btn-xs btn-success me-1" onclick="return confirm('Apakah proses grooming anabul ini sudah selesai dikerjakan?');" title="Selesai">
                                                    <i class="bi bi-check-circle-fill"></i> Selesai
                                                </a>
                                                <a href="hapusgrooming.php?hapus_id=<?php echo $row['id']; ?>" class="btn btn-xs btn-danger" onclick="return confirm('Apakah Anda yakin ingin membatalkan jadwal grooming ini?');" title="Batal">
                                                    <i class="bi bi-trash-fill"></i> Batal
                                                </a>
                                            </td>
                                        </tr>
                                    <?php 
                                        } 
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center text-muted py-3'>Belum ada booking salon/grooming di database.</td></tr>";
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