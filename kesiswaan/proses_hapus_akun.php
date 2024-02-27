<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id_user'])) {
    $id_user = mysqli_real_escape_string($conn, $_GET['id_user']);

    // Query untuk menghapus akun
    $deleteQuery = "DELETE FROM users WHERE id_user = '$id_user'";

    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['result'] = "Akun berhasil dihapus!";
    } else {
        $_SESSION['result'] = "Gagal! Terjadi kesalahan.";
    }

    // Redirect atau sesuaikan dengan logika bisnis lainnya
    header('Location: buat_akun.php');
    exit();
} else {
    // Jika tidak ada parameter id_user pada URL, kembalikan ke halaman buat_akun.php
    header('Location: buat_akun.php');
    exit();
}
