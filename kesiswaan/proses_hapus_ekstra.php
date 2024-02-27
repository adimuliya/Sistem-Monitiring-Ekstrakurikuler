<?php
session_start();
include 'koneksi.php';

if (isset($_GET['id'])) {
    $id_ekstra = $_GET['id'];

    // Lakukan query untuk menghapus ekstrakurikuler dari database
    $deleteQuery = mysqli_query($conn, "DELETE FROM tb_ekstrakurikuler WHERE id_ekstra = '$id_ekstra'");

    if ($deleteQuery) {
        // Redirect atau tampilkan pesan sukses
        header('Location: tambah_ekstra.php');
        exit();
    } else {
        // Tampilkan pesan error jika penghapusan gagal
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Jika parameter id tidak ditemukan
    echo "Parameter ID tidak ditemukan.";
}
