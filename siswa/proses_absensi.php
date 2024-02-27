<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login dan memiliki rolenya
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header('Location: ../login.php');
    exit();
}

// Inisialisasi variabel
$id_user = $id_absensi = $id_ekstra = $absensi_type = $buktiPath = '';
$batas_absensi = '';
$conn = getConnection(); // Assuming you have a function named getConnection in koneksi.php

// Ambil data dari formulir absensi
$id_user = $_SESSION['id_user'];
$id_absensi = $_POST['id_absensi'];
$id_ekstra = $_POST['id_ekstra'];
$absensi_type = $_POST['absensi'];

// Query untuk mendapatkan id_siswa dari tb_siswa berdasarkan id_user yang login
$queryGetIdSiswa = mysqli_query($conn, "SELECT id_siswa FROM tb_siswa WHERE id_user = '$id_user'");

// Cek kesalahan pada query
if (!$queryGetIdSiswa) {
    exit("Error: " . mysqli_error($conn));
}

// Ambil data id_siswa
$id_siswa_row = mysqli_fetch_assoc($queryGetIdSiswa);
$id_siswa = $id_siswa_row['id_siswa'];

// Check apakah siswa sudah melakukan absensi sebelumnya untuk absensi ini
$queryCheckAbsensi = mysqli_query($conn, "SELECT * FROM tb_absensi_siswa WHERE id_siswa = '$id_siswa' AND id_absensi = '$id_absensi'");

// Cek apakah siswa sudah absen sebelumnya
if (mysqli_num_rows($queryCheckAbsensi) > 0) {
    echo '<script>alert("Anda sudah melakukan absensi untuk kegiatan ini.");</script>';
    exit();
}

// Query untuk mendapatkan batas_absensi dari tb_absensi
$queryGetBatasAbsensi = mysqli_query($conn, "SELECT batas_absensi FROM tb_absensi WHERE id_absensi = '$id_absensi'");

// Cek kesalahan pada query
if (!$queryGetBatasAbsensi) {
    exit("Error: " . mysqli_error($conn));
}

// Ambil data batas_absensi
$batas_absensi_row = mysqli_fetch_assoc($queryGetBatasAbsensi);
$batas_absensi = $batas_absensi_row['batas_absensi'];

// Tambahkan proses untuk menyimpan file
if ($absensi_type === 'izin' || $absensi_type === 'sakit') {
    // Tentukan lokasi penyimpanan file
    $uploadDirectory = '../img/';

    // Generate nama unik untuk file
    $uniqueFileName = uniqid('bukti_') . '_' . $_FILES['bukti']['name'];

    // Tentukan path lengkap untuk menyimpan file
    $buktiPath = $uploadDirectory . $uniqueFileName;

    // Pindahkan file dari temporary storage ke lokasi yang ditentukan
    move_uploaded_file($_FILES['bukti']['tmp_name'], $buktiPath);
}

date_default_timezone_set('Asia/Jakarta');
// Ambil waktu sekarang
$current_time = date('Y-m-d H:i:s');

// Cek apakah sudah melewati batas waktu absensi
if ($current_time > $batas_absensi) {
    $absensi_type = 'alpha';

    // Tampilkan pesan khusus jika siswa absen melebihi batas_absensi
    echo '<script>alert("Anda telah melebihi batas waktu absensi. Absensi dianggap sebagai alpha."); window.location.href = "siswa.php";</script>';
} else {
    // Query untuk memasukkan data ke tb_absensi_siswa
    $queryInsertAbsensi = mysqli_query($conn, "INSERT INTO tb_absensi_siswa (id_absensi, id_user, id_siswa, id_ekstra, waktu_absensi, jenis_absensi, bukti) VALUES ('$id_absensi', '$id_user', '$id_siswa', '$id_ekstra', '$current_time', '$absensi_type','$buktiPath')");

    // Cek kesalahan pada query
    if (!$queryInsertAbsensi) {
        echo '<script>alert("Error: ' . mysqli_error($conn) . '");</script>';
        exit();
    }

    // Redirect ke halaman dashboard atau halaman lain sesuai kebutuhan
    echo '<script>alert("Absensi berhasil!"); window.location.href = "siswa.php";</script>';
    exit();
}
