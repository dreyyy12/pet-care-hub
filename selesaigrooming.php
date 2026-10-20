<?php
ob_start();
session_start();

// Proteksi halaman admin
if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}

include("includes/config.php");

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_selesai = intval($_GET['id']);

    // 1. Ambil data lengkap antrean aktif yang mau diselesaikan berdasarkan ID
    $ambil_data = mysqli_query($conn, "SELECT * FROM booking_grooming WHERE id = $id_selesai");
    
    if ($ambil_data && mysqli_num_rows($ambil_data) > 0) {
        $row = mysqli_fetch_assoc($ambil_data);
        
        $username    = $row['username'];
        $layanan     = $row['layanan'];
        $harga       = $row['harga'];
        $nama_hewan  = $row['nama_hewan'];
        $jenis_hewan = $row['jenis_hewan'];
        $checkin     = $row['checkin'];
        $checkin_jam = $row['checkin_jam'];

        // 2. Pindahkan (Insert) data tersebut ke dalam tabel history_grooming
        $sql_history = "INSERT INTO history_grooming (username, layanan, harga, nama_hewan, jenis_hewan, checkin, checkin_jam) 
                        VALUES ('$username', '$layanan', '$harga', '$nama_hewan', '$jenis_hewan', '$checkin', '$checkin_jam')";
        
        if (mysqli_query($conn, $sql_history)) {
            // 3. Jika berhasil disalin, hapus data lamanya dari tabel antrean aktif booking_grooming
            mysqli_query($conn, "DELETE FROM booking_grooming WHERE id = $id_selesai");
            
            // Kembalikan ke halaman kelola grooming dengan aman
            header("Location: kelolagrooming.php");
            exit();
        } else {
            echo "<div style='color: red; padding: 20px;'>Gagal memindahkan data ke riwayat: " . mysqli_error($conn) . "</div>";
        }
    } else {
        header("Location: kelolagrooming.php");
        exit();
    }
} else {
    header("Location: kelolagrooming.php");
    exit();
}
?>