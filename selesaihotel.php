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

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_selesai = intval($_GET['id']);

    // 1. Ambil data lengkap antrean aktif pet hotel yang mau diselesaikan berdasarkan id_booking
    $ambil_data = mysqli_query($conn, "SELECT * FROM booking_hotel WHERE id_booking = $id_selesai");
    
    if ($ambil_data && mysqli_num_rows($ambil_data) > 0) {
        $row = mysqli_fetch_assoc($ambil_data);
        
        // Sesuaikan dengan variabel kolom dari tabel booking_hotel kamu
        $username    = $row['username'];
        $tipe_kamar  = $row['kamar']; // Mengambil info kolom kamar
        $harga       = $row['harga'];
        $nama_hewan  = $row['nama_hewan'];
        $checkin     = $row['checkin'];
        $checkout    = $row['checkout'];

        // 2. Pindahkan (Insert) data tersebut ke dalam tabel history_hotel
        $sql_history = "INSERT INTO history_hotel (username, tipe_kamar, harga, nama_hewan, tanggal_checkin, tanggal_checkout) 
                        VALUES ('$username', '$tipe_kamar', '$harga', '$nama_hewan', '$checkin', '$checkout')";
        
        if (mysqli_query($conn, $sql_history)) {
            // 3. Jika berhasil disalin, hapus data lamanya dari tabel antrean aktif booking_hotel
            mysqli_query($conn, "DELETE FROM booking_hotel WHERE id_booking = $id_selesai");
            
            // Kembalikan ke halaman kelola hotel dengan aman
            header("Location: kelolahotel.php");
            exit();
        } else {
            echo "<div style='color: red; padding: 20px;'>Gagal memindahkan data hotel ke riwayat: " . mysqli_error($conn) . "</div>";
        }
    } else {
        header("Location: kelolahotel.php");
        exit();
    }
} else {
    header("Location: kelolahotel.php");
    exit();
}
?>