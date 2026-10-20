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

    // 1. Ambil data lengkap dari tabel booking_konsultasi aktif
    $ambil_data = mysqli_query($conn, "SELECT * FROM booking_konsultasi WHERE id_booking = $id_selesai");
    
    if ($ambil_data && mysqli_num_rows($ambil_data) > 0) {
        $row = mysqli_fetch_assoc($ambil_data);
        
        $username       = $row['username'];
        $paket_konsul   = $row['paket_konsul'];
        $harga          = $row['harga'];
        $nama_hewan     = $row['nama_hewan'];
        $tanggal_konsul = $row['tanggal_konsul'];
        $jam_konsul     = $row['jam_konsul'];

        // 2. Pindahkan (Insert) data ke dalam tabel history_dokter
        $sql_history = "INSERT INTO history_dokter (username, paket_konsul, harga, nama_hewan, tanggal_konsul, jam_konsul) 
                        VALUES ('$username', '$paket_konsul', '$harga', '$nama_hewan', '$tanggal_konsul', '$jam_konsul')";
        
        if (mysqli_query($conn, $sql_history)) {
            // 3. Jika sukses, hapus data aslinya dari antrean aktif
            mysqli_query($conn, "DELETE FROM booking_konsultasi WHERE id_booking = $id_selesai");
            
            // Kembalikan ke halaman kelola dokter
            header("Location: keloladokter.php");
            exit();
        } else {
            echo "<div style='color: red; padding: 20px;'>Gagal memindahkan data dokter ke riwayat: " . mysqli_error($conn) . "</div>";
        }
    } else {
        header("Location: keloladokter.php");
        exit();
    }
} else {
    header("Location: keloladokter.php");
    exit();
}
?>