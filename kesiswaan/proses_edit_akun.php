<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $newUsername = mysqli_real_escape_string($conn, $_POST['new_username']);
    $oldUsername = mysqli_real_escape_string($conn, $_POST['old_username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Query untuk mengupdate username
    $updateQuery = "UPDATE users SET username = '$newUsername', role = '$role' WHERE username = '$oldUsername'";

    if (mysqli_query($conn, $updateQuery)) {
        $_SESSION['result'] = "Akun berhasil diubah!";
    } else {
        $_SESSION['result'] = "Gagal! Terjadi kesalahan.";
    }

    // Redirect atau sesuaikan dengan logika bisnis lainnya
    header('Location: buat_akun.php');
    exit();
}
