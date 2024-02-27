<?php
session_start();
include 'koneksi.php';

// Pastikan form editAbsensiIdSiswa terdefinisi
if (isset($_POST['editAbsensiIdSiswa'])) {
    $idSiswa = $_POST['editAbsensiIdSiswa'];
    $jenisAbsensi = $_POST['editJenisAbsensi'];

    // Lakukan validasi atau manipulasi data jika diperlukan
    // ...

    // Update data jenis_absensi di database
    $queryUpdate = mysqli_query($conn, "UPDATE tb_absensi_siswa SET jenis_absensi = '$jenisAbsensi' WHERE id_absensi_siswa = $idSiswa");

    if ($queryUpdate) {
        // Redirect ke halaman cek_absensi.php setelah berhasil update
        header('Location: cek_absensi.php');
        exit();
    } else {
        // Handle jika terjadi kesalahan saat update
        echo "Error: " . mysqli_error($conn);
    }
} else {
    // Handle jika data tidak diterima dari form
    echo "Error: Data tidak diterima dari form.";
}
