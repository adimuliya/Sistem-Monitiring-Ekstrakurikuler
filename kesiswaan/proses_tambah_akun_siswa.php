<?php
session_start();
include 'koneksi.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $nama_siswa = $_POST['nama_siswa'];
    $nis = $_POST['nis'];
    $kelas = $_POST['kelas'];
    $jurusan = $_POST['jurusan'];
    $sub_kelas = $_POST['sub_kelas'];

    // Gabungkan nilai 'kelas', 'jurusan', dan 'sub_kelas' menjadi satu
    $kelas_lengkap = $kelas . " " . $jurusan . " " . $sub_kelas;

    // Hash password sebelum disimpan
    

    // Query untuk menambahkan akun siswa ke tabel 'users'
    $queryUsers = "INSERT INTO users (username, password, role) VALUES ('$username', '$password', '$role')";
    $resultUsers = mysqli_query($conn, $queryUsers);

    if ($resultUsers) {
        // Jika akun siswa berhasil ditambahkan, ambil id_user yang baru saja dibuat
        $id_user = mysqli_insert_id($conn);

        // Query untuk menambahkan siswa ke tabel 'tb_siswa' dengan 'kelas' yang sudah digabungkan
        $querySiswa = "INSERT INTO tb_siswa (id_user, nama_siswa, nis, kelas) 
                       VALUES ('$id_user', '$nama_siswa', '$nis', '$kelas_lengkap')";
        $resultSiswa = mysqli_query($conn, $querySiswa);

        if ($resultSiswa) {
            $_SESSION['notification']['type'] = 'success';
            $_SESSION['notification']['message'] = 'Data berhasil dimasukkan!';
        } else {
            $_SESSION['notification']['type'] = 'danger';
            $_SESSION['notification']['message'] = 'Gagal memasukkan data siswa. Error: ' . mysqli_error($conn);
        }
    } else {
        $_SESSION['notification']['type'] = 'danger';
        $_SESSION['notification']['message'] = 'Gagal memasukkan data pengguna. Error: ' . mysqli_error($conn);
    }

    header('Location: buat_akun_siswa.php'); // Ganti sesuai halaman yang sesuai
    exit();
} else {
    header('Location: buat_akun_siswa.php'); // Ganti sesuai halaman yang sesuai
    exit();
}
