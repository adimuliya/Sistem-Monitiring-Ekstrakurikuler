<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $role =  mysqli_real_escape_string($conn, $_POST['role']);

    // Periksa apakah username sudah ada
    $checkUsernameQuery = "SELECT COUNT(*) as count FROM users WHERE username = '$username'";
    $checkUsernameResult = mysqli_query($conn, $checkUsernameQuery);
    $checkUsernameCount = mysqli_fetch_assoc($checkUsernameResult)['count'];

    if ($checkUsernameCount > 0) {
        $_SESSION['result'] = "Gagal! Username sudah digunakan.";
    } else {
        // Simpan data ke tabel users
        $query = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
        if (mysqli_query($conn, $query)) {
            $_SESSION['result'] = "Data berhasil dimasukkan!";
        } else {
            $_SESSION['result'] = "Gagal! Terjadi kesalahan.";
        }
    }
}

// Redirect atau sesuaikan dengan logika bisnis lainnya
header('Location: buat_akun.php');
exit();
