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

<body class="sb-nav-fixed">
    <?php include "bagiankode/menunav.php"; ?>
    <div id="layoutSidenav">
        <?php include "bagiankode/menu.php"; ?>
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <h1 class="mt-4">Riwayat Pet Hotel (Check-Out)</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Hotel</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header bg-success text-white fw-bold">
                            <i class="bi bi-clock-history me-1"></i> Data Semua Reservasi Kamar yang Telah Selesai
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered table-hover" style="font-size: 11pt;">
                                <thead class="table-dark">
                                    <tr style="text-align: center;">
                                        <th style="width: 5%;">No.</th>
                                        <th>Username</th>
                                        <th>Tipe Kamar</th>
                                        <th>Total Biaya</th>
                                        <th>Nama Hewan</th>
                                        <th>Durasi Menginap</th>
                                        <th>Waktu Selesai / Check-Out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // Diurutkan berdasarkan tanggal selesai paling terbaru (data fresh di atas)
                                    $query = mysqli_query($conn, "SELECT * FROM history_hotel ORDER BY tanggal_selesai DESC");
                                    
                                    if ($query && mysqli_num_rows($query) > 0) {
                                        while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $no++; ?>.</td>
                                            <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                                            <td><span class="badge bg-success"><?php echo htmlspecialchars($row['tipe_kamar']); ?></span></td>
                                            <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_hewan']); ?></td>
                                            <td>
                                                <small class="text-muted">
                                                    Check-in: <strong><?php echo date('d M Y', strtotime($row['tanggal_checkin'])); ?></strong><br>
                                                    Check-out: <strong><?php echo date('d M Y', strtotime($row['tanggal_checkout'])); ?></strong>
                                                </small>
                                            </td>
                                            <td>
                                                <span class="text-success fw-bold">
                                                    <i class="bi bi-calendar-check me-1"></i>
                                                    <?php echo date('d M Y | H:i', strtotime($row['tanggal_selesai'])); ?> WIB
                                                </span>
                                            </td>
                                        </tr>
                                    <?php 
                                        } 
                                    } else {
                                        echo "<tr><td colspan='7' class='text-center text-muted py-3'>Belum ada riwayat check-out pet hotel yang diselesaikan.</td></tr>";
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