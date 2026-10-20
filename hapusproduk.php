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

// Pastikan parameter ID ada di URL dan tidak kosong
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_hapus = intval($_GET['id']); // Mengamankan input agar murni angka integer ID

    // Jalankan query hapus data produk berdasarkan ID utama
    $sql_hapus = "DELETE FROM produk WHERE id = $id_hapus";
    
    if ($conn->query($sql_hapus) === TRUE) {
        // Jika sukses dihapus, langsung dialihkan kembali ke halaman utama kelolaproduk.php
        header("Location: kelolaproduk.php");
        exit();
    } else {
        echo "<div style='color: red; padding: 20px;'>Gagal menghapus produk: " . $conn->error . "</div>";
    }
} else {
    // Jika ID tidak ditemukan di URL, kembalikan ke halaman utama produk
    header("Location: kelolaproduk.php");
    exit();
}

// Tutup koneksi database secara rapi
if (isset($conn)) {
    $conn->close();
}
?>