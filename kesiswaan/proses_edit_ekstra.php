<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login dan memiliki rolenya
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'kesiswaan') {
    header('Location: ../login.php'); // Ganti login.php dengan halaman login dan sesuaikan dengan struktur folder
    exit();
}

if (isset($_POST['editSubmit'])) {
    // Ambil nilai dari formulir
    $idEkstra = $_POST['id'];
    $namaEkstra = $_POST['editNamaEkstra'];
    $idPembimbing = $_POST['editPilihPembimbing'];
    $namaPembimbing = $_POST['editNamaPembimbing'];
    $jadwalHari = $_POST['editJadwalHari'];
    $jadwalJam = $_POST['editJadwalJam'];

    // Perbarui data ekstrakurikuler dalam tabel tb_ekstrakurikuler
    $updateQuery = "UPDATE tb_ekstrakurikuler SET nama_ekstra = '$namaEkstra', id_user = '$idPembimbing', nama_pembimbing = '$namaPembimbing', jadwal_hari = '$jadwalHari', jadwal_jam = '$jadwalJam' WHERE id_ekstra = $idEkstra";

    $updateResult = mysqli_query($conn, $updateQuery);

    // Periksa apakah pembaruan berhasil
    if ($updateResult) {
        // Redirect atau tampilkan pesan sukses
        header('Location: tambah_ekstra.php');
        exit();
    } else {
        // Tampilkan pesan error jika pembaruan gagal
        echo "Error: " . mysqli_error($conn);
    }
}
