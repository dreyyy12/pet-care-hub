<?php
ob_start();
session_start();

// Proteksi halaman admin: Pastikan user sudah login
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include "includes/config.php";

// Ambil parameter email dari URL (?hapusemail=...)
if (isset($_GET["hapusemail"]) && !empty($_GET["hapusemail"])) {
    $email_hapus = $conn->real_escape_string($_GET["hapusemail"]);

    // Jalankan query hapus data customer berdasarkan email
    $sql_hapus = "DELETE FROM users WHERE email = '$email_hapus'";
    
    if (mysqli_query($conn, $sql_hapus)) {
        // Jika sukses dihapus, langsung alihkan kembali ke halaman utama inputcustomer.php
        header("Location: inputcust.php");
        exit();
    } else {
        echo "<div style='color: red; padding: 20px;'>Gagal menghapus data dari database: " . mysqli_error($conn) . "</div>";
    }
} else {
    echo "<div style='color: red; padding: 20px;'>Error: Parameter email tidak ditemukan di URL!</div>";
}

// Tutup koneksi database secara rapi
if (isset($conn)) {
    $conn->close();
}
?>