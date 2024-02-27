<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login dan memiliki rolenya
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
    header('Location: ../login.php');
    exit();
}

// Ambil nilai id_absensi dari URL
$idAbsensi = isset($_GET['id_absensi']) ? $_GET['id_absensi'] : 0;

// Query untuk mendapatkan informasi absensi berdasarkan id_absensi
$queryAbsensiDetail = mysqli_query($conn, "
    SELECT tb_absensi.*, tb_ekstrakurikuler.nama_ekstra
    FROM tb_absensi
    JOIN tb_ekstrakurikuler ON tb_absensi.id_ekstra = tb_ekstrakurikuler.id_ekstra
    WHERE tb_absensi.id_absensi = '$idAbsensi'
");

// Cek kesalahan pada query
if (!$queryAbsensiDetail) {
    exit("Error: " . mysqli_error($conn));
}

// Mengambil data absensi
$absensiDetail = mysqli_fetch_assoc($queryAbsensiDetail);

// Cek apakah data absensi ditemukan
if (!$absensiDetail) {
    exit("Error: Absensi not found");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Sistem Monitoring Ekstrakurikuler | Form Absensi</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
    <div class="container mt-5">
        <h2>Form Absensi</h2>
        <form action="proses_absensi.php" method="post">
            <input type="hidden" name="id_user" value="<?php echo $_SESSION['id_user']; ?>">
            <input type="hidden" name="id_absensi" value="<?php echo $absensiDetail['id_absensi']; ?>">

            <!-- Input nama_ekstra yang Terisi Otomatis -->
            <div class="form-group">
                <label for="nama_ekstra">Nama Ekstrakurikuler:</label>
                <input type="text" class="form-control" id="nama_ekstra" name="nama_ekstra" value="<?php echo isset($absensiDetail['nama_ekstra']) ? $absensiDetail['nama_ekstra'] : ''; ?>" readonly>
            </div>

            <input type="hidden" name="id_ekstra" value="<?php echo $absensiDetail['id_ekstra']; ?>">

            <!-- Input Nama Siswa yang Terisi Otomatis -->
            <div class="form-group">
                <label for="nama_siswa">Nama Siswa:</label>
                <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="<?php echo $_SESSION['username']; ?>" readonly>
            </div>

            <!-- Input Kelas yang Terisi Otomatis -->
            <div class="form-group">
                <label for="kelas">Kelas:</label>
                <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo $_SESSION['kelas']; ?>" readonly>
            </div>

            <!-- Input Waktu Absensi yang Terisi Otomatis -->
            <div class="form-group">
                <label for="waktu_absensi">Batas Absensi:</label>
                <input type="text" class="form-control" id="batas_absensi" name="batas_absensi" value="<?php echo $absensiDetail['batas_absensi']; ?>" readonly>
            </div>

            <!-- Pilihan Absensi -->
            <div class="form-group">
                <label for="absensi">Absensi:</label>
                <select class="form-control" id="absensi" name="absensi">
                    <option value="hadir">Hadir</option>
                    <option value="izin">Izin</option>
                    <option value="sakit">Sakit</option>
                </select>
            </div>
            <div class="form-group">
                <label for="bukti">Unggah Bukti (Jika Izin/Sakit):</label>
                <input type="file" class="form-control-file" id="bukti" name="bukti">
            </div>

            <!-- Tambahkan input lain sesuai kebutuhan -->

            <button type="submit" class="btn btn-primary">Submit Absensi</button>
        </form>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>