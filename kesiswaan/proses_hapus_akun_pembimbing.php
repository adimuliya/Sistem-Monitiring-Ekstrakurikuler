<?php
session_start();
include 'koneksi.php';

if (isset($_GET['username'])) {
    $username = mysqli_real_escape_string($conn, $_GET['username']);

    // Query untuk menghapus akun
    $deleteQuery = "DELETE FROM users WHERE username = '$username'";

    if (mysqli_query($conn, $deleteQuery)) {
        $_SESSION['result'] = "Akun berhasil dihapus!";
    } else {
        $_SESSION['result'] = "Gagal! Terjadi kesalahan.";
    }

    // Redirect atau sesuaikan dengan logika bisnis lainnya
    header('Location: buat_akun_pembimbing.php');
    exit();
} else {
    // Jika tidak ada parameter username pada URL, kembalikan ke halaman buat_akun.php
    header('Location: buat_akun_pembimbing.php');
    exit();
}
