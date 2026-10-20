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

// Logika Hapus/Batal Antrean Hotel
if (isset($_GET['hapus_nota'])) {
    $nota_hapus = $conn->real_escape_string($_GET['hapus_nota']);
    mysqli_query($conn, "DELETE FROM booking_hotel WHERE id_booking = '$nota_hapus'");
    header("Location: kelolahotel.php");
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
                    <h1 class="mt-4">Kelola Booking Pet Hotel</h1>
                    <ol class="breadcrumb mb-4">
                        <li class="breadcrumb-item"><a href="dashboard.php" class="text-decoration-none">Dashboard</a></li>
                        <li class="breadcrumb-item active">Booking Pet Hotel</li>
                    </ol>

                    <div class="card mb-4">
                        <div class="card-header bg-success text-white">
                            <i class="bi bi-house-door-fill me-1"></i> Daftar Check-in Kamar Penginapan Hewan
                        </div>
                        <div class="card-body">
                            <table class="table table-striped table-bordered table-hover font-size-sm" style="font-size: 11pt;">
                                <thead class="table-dark">
                                    <tr style="text-align: center;">
                                        <th style="width: 5%;">No.</th>
                                        <th>Username</th>
                                        <th>Tipe Kamar</th>
                                        <th>Total Biaya</th>
                                        <th>Nama Hewan</th>
                                        <th>Tanggal Check-In</th>
                                        <th>Tanggal Check-Out</th>
                                        <th style="width: 12%;">Aksi</th> </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $no = 1;
                                    $query = mysqli_query($conn, "SELECT * FROM booking_hotel ORDER BY checkin DESC");
                                    
                                    if ($query && mysqli_num_rows($query) > 0) {
                                        while ($row = mysqli_fetch_array($query)) {
                                    ?>
                                        <tr>
                                            <td style="text-align: center;"><?php echo $no++; ?>.</td>
                                            <td><strong><?php echo htmlspecialchars($row['username']); ?></strong></td>
                                            <td><?php echo htmlspecialchars($row['kamar']); ?></td>
                                            <td>Rp. <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                            <td><?php echo htmlspecialchars($row['nama_hewan']); ?></td>
                                            <td><span class="text-success fw-bold"><?php echo date('d M Y', strtotime($row['checkin'])); ?></span></td>
                                            <td><span class="text-danger fw-bold"><?php echo date('d M Y', strtotime($row['checkout'])); ?></span></td>
                                            <td style="text-align: center; white-space: nowrap;">
                                                <a href="selesaihotel.php?id=<?php echo $row['id_booking']; ?>" class="btn btn-xs btn-success me-1" onclick="return confirm('Apakah anabul sudah check-out dan proses pet hotel ini telah selesai?');" title="Selesai">
                                                    <i class="bi bi-check-circle-fill"></i> Selesai
                                                </a>
                                                
                                                <a href="kelolahotel.php?hapus_nota=<?php echo urlencode($row['id_booking']); ?>" class="btn btn-xs btn-danger" onclick="return confirm('Batalkan pesanan kamar penginapan ini?');" title="Batal">
                                                    <i class="bi bi-trash-fill"></i> Batal
                                                </a>
                                            </td>
                                        </tr>
                                    <?php 
                                        } 
                                    } else {
                                        echo "<tr><td colspan='8' class='text-center text-muted py-3'>Belum ada anabul yang menginap di hotel.</td></tr>";
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