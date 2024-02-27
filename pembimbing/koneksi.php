<?php
// Konfigurasi database
$servername = "localhost"; // Ganti sesuai dengan server Anda
$username = "root"; // Ganti sesuai dengan username database Anda
$password = ""; // Dikosongkan jika database tidak memiliki password
$database = "dbmonitoring_siswa"; // Ganti sesuai dengan nama database Anda

// Membuat koneksi
$conn = mysqli_connect($servername, $username, $password, $database);

// Memeriksa koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Lakukan operasi database atau query lainnya di sini

// Tutup koneksi

