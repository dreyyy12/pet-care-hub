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

// Pastikan parameter hapus_id ada di URL dan berupa angka
if (isset($_GET['hapus_id']) && !empty($_GET['hapus_id'])) {
    $id_hapus = intval($_GET['hapus_id']); // Mengamankan input agar murni angka integer

    // Jalankan query hapus data berdasarkan id utama
    $sql_hapus = "DELETE FROM booking_grooming WHERE id = $id_hapus";
    
    if (mysqli_query($conn, $sql_hapus)) {
        // Jika sukses dihapus, langsung dialihkan kembali ke halaman utama kelolagrooming.php
        header("Location: kelolagrooming.php");
        exit();
    } else {
        echo "<div style='color: red; padding: 20px;'>Gagal menghapus antrean: " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div style='color: red; padding: 20px;'>Error: ID Antrean tidak ditemukan di URL!</div>";
}

// Tutup koneksi database secara rapi
if (isset($conn)) {
    $conn->close();
}
?>