<?php 
ob_start();
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

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
                    <h1 class="mt-4">Riwayat Penyelesaian Jasa</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Riwayat Grooming</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header bg-success text-white fw-bold">
                            <i class="bi bi-clock-history me-1"></i> Data Semua Grooming yang Telah Selesai
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered table-hover" style="font-size: 11pt;">
                                <thead class="table-dark">
                                    <tr style="text-align: center;">
                                        <th style="width: 5%;">No.</th>
                                        <th>Username</th>
                                        <th>Paket Grooming</th>
                                        <th>Biaya Jasa</th>
                                        <th>Nama Hewan</th>
                                        <th>Jenis Hewan</th>
                                        <th>Jadwal Booking</th>
                                        <th>Waktu Selesai Pengerjaan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    // Diurutkan berdasarkan tanggal selesai paling terbaru (data fresh di atas)
                                    $query = mysqli_query($conn, "SELECT * FROM history_grooming ORDER BY tanggal_selesai DESC");
                                    
                                    if ($query && mysqli_num_rows($query) > 0) {
                                        while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $no++; ?>.</td>
                                            <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($row['layanan']); ?></td>
                                            <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_hewan']); ?></td>
                                            <td><?php echo htmlspecialchars($row['jenis_hewan']); ?></span></td>
                                            <td><?php echo date('d M Y', strtotime($row['checkin'])); ?> - <?php echo htmlspecialchars($row['checkin_jam']); ?></td>
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
                                        echo "<tr><td colspan='8' class='text-center text-muted py-3'>Belum ada riwayat pengerjaan grooming yang diselesaikan.</td></tr>";
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