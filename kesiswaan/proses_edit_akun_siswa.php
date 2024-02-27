<?php
include("koneksi.php"); // Sertakan file koneksi database Anda

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Dapatkan data yang dikirimkan dari formulir
    $newUsername = $_POST["new_username"];
    $oldUsername = $_POST["old_username"];
    $role = $_POST["role"];
    $newNamaSiswa = $_POST["new_nama_siswa"];
    $oldNamaSiswa = $_POST["old_nama_siswa"];
    $newNis = $_POST["new_nis"];
    $oldNis = $_POST["old_nis"];
    $newKelas = $_POST["new_kelas"];
    $newJurusan = $_POST["new_jurusan"];
    $newSubKelas = $_POST["new_sub_kelas"];

    // Gabungkan nilai new_kelas, new_jurusan, dan new_sub_kelas menjadi satu variabel
    $combinedKelas = $newKelas . $newJurusan . $newSubKelas;

    // Lakukan query update
    $updateQuery = "UPDATE users
                    SET username = '$newUsername', role = '$role'
                    WHERE username = '$oldUsername'";

    $updateSiswaQuery = "UPDATE tb_siswa
                         SET nama_siswa = '$newNamaSiswa', nis = '$newNis', kelas = '$combinedKelas'
                         WHERE nama_siswa = '$oldNamaSiswa' AND nis = '$oldNis'";

    // Eksekusi query update
    $updateUserResult = mysqli_query($conn, $updateQuery);
    $updateSiswaResult = mysqli_query($conn, $updateSiswaQuery);

    // Periksa apakah update berhasil
    if ($updateUserResult && $updateSiswaResult) {
        // Pesan berhasil dengan JavaScript
        echo "<script>
                alert('Data siswa berhasi diedit');
                window.location.href = 'buat_akun_siswa.php';
              </script>";
        exit();
    } else {
        // Pesan kesalahan dengan JavaScript
        echo "<script>
                alert('gagal mengedit data siswa " . mysqli_error($conn) . "');
                window.location.href = 'buat_akun_siswa.php';
              </script>";
        exit();
    }
} else {
    // Redirect ke halaman kesalahan jika formulir tidak dikirim dengan benar
    header("Location: buat_akun_siswa.php");
    exit();
}
