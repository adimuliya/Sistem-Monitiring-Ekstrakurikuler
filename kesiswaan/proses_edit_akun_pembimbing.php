<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login dan memiliki rolenya
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'kesiswaan') {
    header('Location: ../login.php'); // Ganti login.php dengan halaman login dan sesuaikan dengan struktur folder
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    // Ambil data dari formulir
    $newUsername = mysqli_real_escape_string($conn, $_POST['new_username']);
    $oldUsername = mysqli_real_escape_string($conn, $_POST['old_username']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    // Query untuk mengupdate data pembimbing
    $updateQuery = "UPDATE users SET username = '$newUsername', role = '$role' WHERE username = '$oldUsername'";
if ($updateQuery) {
    # code...
}
    if (mysqli_query($conn, $updateQuery)) {
        header('Location: kesiswaan.php'); // Ganti kesiswaan.php dengan halaman yang sesuai
        exit();
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }
} else {
    // Jika tidak ada data yang dikirimkan, kembali ke halaman kesiswaan.php atau halaman lainnya
    header('Location: kesiswaan.php');
    exit();
}
