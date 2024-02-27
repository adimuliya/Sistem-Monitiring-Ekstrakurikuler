<?php
session_start();
include 'koneksi.php';

// Form Submit
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Periksa apakah pengguna sudah login dan memiliki rolenya
    if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'pembimbing') {
        exit("Error: Unauthorized access");
    }

    // Gantilah dengan cara yang sesuai untuk mendapatkan id_user sesuai dengan sesi atau logika aplikasi Anda.
    $usernamePembimbing = $_SESSION['username'];
    $queryPembimbing = mysqli_query($conn, "SELECT id_user FROM users WHERE username = '$usernamePembimbing'");
    $pembimbingData = mysqli_fetch_assoc($queryPembimbing);

    $idPembimbing = $pembimbingData['id_user'];

    $id_ekstra = $_POST['id_ekstrakurikuler']; // Ambil id_ekstra dari form.
    $hari = $_POST['hari'];
    $batas_absensi = $_POST['batas_absensi'];
    $catatan_jurnal = $_POST['catatan_jurnal'];


    // Selanjutnya, gunakan $id_user dan $id_ekstra dalam query INSERT ke tb_absensi.
    $queryInsertAbsensi = mysqli_query($conn, "INSERT INTO tb_absensi (id_user, id_ekstra, hari, batas_absensi, catatan_jurnal) VALUES ('$idPembimbing', '$id_ekstra', '$hari', '$batas_absensi', '$catatan_jurnal')");

    if ($queryInsertAbsensi) {
        // Menampilkan alert jika berhasil
        echo "<script>alert('Data absensi berhasil disimpan.'); window.location.href = 'buat_absensi_siswa.php';</script>";
      
    } else {
        // Menampilkan alert jika gagal
        echo "<script>alert('Error: " . mysqli_error($conn) . "');window.location.href = 'buat_absensi_siswa.php';</script>";
    }
} else {
    echo "Error: Invalid request method";
}
?>