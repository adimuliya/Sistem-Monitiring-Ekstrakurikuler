<?php
// Mulai session dan sertakan file koneksi
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login dan memiliki rolenya
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'pembimbing') {
    header('Location: ../login.php'); // Redirect ke halaman login jika belum login atau bukan pembimbing
    exit();
}

// Mendapatkan informasi pembimbing yang sedang login
$usernamePembimbing = $_SESSION['username'];
$queryPembimbing = mysqli_query($conn, "SELECT id_user FROM users WHERE username = '$usernamePembimbing'");

// Cek kesalahan pada query
if (!$queryPembimbing) {
    exit("Error: " . mysqli_error($conn));
}

$pembimbingData = mysqli_fetch_assoc($queryPembimbing);

// Pengecekan apakah query mendapatkan hasil
if (!$pembimbingData) {
    // Handle jika query tidak menghasilkan data pembimbing.
    exit("Error: Query tidak menghasilkan data pembimbing.");
}

$idPembimbing = $pembimbingData['id_user'];

// Dapatkan id ekstrakurikuler dari id user yang sedang login
$queryIdEkstrakurikuler = mysqli_query($conn, "SELECT id_ekstra FROM tb_ekstrakurikuler WHERE id_user = $idPembimbing LIMIT 1");

// Cek kesalahan pada query
if (!$queryIdEkstrakurikuler) {
    exit("Error: " . mysqli_error($conn));
}

$idEkstrakurikulerData = mysqli_fetch_assoc($queryIdEkstrakurikuler);

// Pengecekan apakah query mendapatkan hasil
if (!$idEkstrakurikulerData) {
    // Handle jika query tidak menghasilkan data ekstrakurikuler.
    exit("Error: Anda tidak memiliki ekstrakurikuler yang diampu. Silakan hubungi administrator.");
}

$idEkstrakurikuler = $idEkstrakurikulerData['id_ekstra'];

// Mendapatkan daftar ekstrakurikuler yang diampu oleh pembimbing
$queryEkstrakurikuler = mysqli_query($conn, "SELECT a.id_ekstra, a.nama_ekstra, a.nama_pembimbing FROM tb_ekstrakurikuler a WHERE a.id_user = $idPembimbing");

// Cek kesalahan pada query
if (!$queryEkstrakurikuler) {
    exit("Error: " . mysqli_error($conn));
}

// Menggunakan fetch_all untuk mendapatkan semua data ekstrakurikuler yang diampu
$ekstrakurikulerList = mysqli_fetch_all($queryEkstrakurikuler, MYSQLI_ASSOC);

// Pengecekan apakah query mendapatkan hasil
if (!$ekstrakurikulerList) {
    // Handle jika query tidak menghasilkan data ekstrakurikuler.
    exit("Error: Anda tidak memiliki ekstrakurikuler yang diampu. Silakan hubungi administrator.");
}

$namaPembimbing = $ekstrakurikulerList[0]['nama_pembimbing'] ?? ''; // Menggunakan null coalescing operator untuk penanganan default

                $idPembimbing = $pembimbingData['id_user'];

                // Periksa apakah data tanggal sudah dikirimkan dari formulir filter
                $tanggalMulai = isset($_GET['start_date']) ? $_GET['start_date'] : '';
                $tanggalSelesai = isset($_GET['end_date']) ? $_GET['end_date'] : '';
                $queryAbsensi = mysqli_query($conn, "SELECT tb_absensi.hari, tb_absensi.batas_absensi, tb_absensi.catatan_jurnal, tb_ekstrakurikuler.nama_ekstra
                                  FROM tb_absensi
                                  JOIN tb_ekstrakurikuler ON tb_absensi.id_ekstra = tb_ekstrakurikuler.id_ekstra
                                 
                                  AND tb_absensi.hari BETWEEN '$tanggalMulai' AND '$tanggalSelesai'");



                // Cek kesalahan pada query
                if (!$queryAbsensi) {
                    exit("Error: " . mysqli_error($conn));
                }

                $laporanAbsensiList = mysqli_fetch_all($queryAbsensi, MYSQLI_ASSOC);
// Dapatkan daftar absensi
$queryAbsensi = mysqli_query($conn, "SELECT tb_absensi.hari, tb_absensi.batas_absensi, tb_absensi.catatan_jurnal, tb_ekstrakurikuler.nama_ekstra FROM tb_absensi
                      JOIN tb_ekstrakurikuler ON tb_absensi.id_ekstra = tb_ekstrakurikuler.id_ekstra
                      WHERE tb_absensi.id_ekstra = $idEkstrakurikuler");

if (!$queryAbsensi) {
    exit("Error: " . mysqli_error($conn));
}

$absensiList = mysqli_fetch_all($queryAbsensi, MYSQLI_ASSOC);

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/print.css" media="print">

    <!-- Custom styles for print -->
    <style>
        /* Gaya cetak khusus untuk laporan */
        body {
            font-family: 'Times New Roman', Times, serif;
            color: black;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .kop-surat {

            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        @media print {
            .btn-print {
                display: none;
                /* Sembunyikan kop-surat dan tombol cetak saat dicetak */
            }
        }
    </style>
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="kop-surat mb-4">
            <h3 class="h3">Laporan Absensi Ekstrakurikuler</h3>
            <div class="header-surat">
                <p class="mb-0">Pembimbing: <?= $namaPembimbing; ?></p>
                <h1>SMK Negeri 1 Jenangan Ponorogo</h1>
                <p>Jl. Nama Niken Gandini No. 98, Plampitan, Setono, kec. Jenangan Kota, Kabupaten Ponorogo, Jawa Timur, 63492</p>
                <p>Periode: <?php echo $tanggalMulai; ?> sampai <?php echo $tanggalSelesai; ?></p>
            </div>
        </div>
    </div>
    <hr>


    <div class="table-responsive">
        <table class="table table-bordered" id="absensiTable" width="100%" cellspacing="0">
            <thead class="">
                <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Nama Ekstrakurikuler</th>
                    <th>Batas Absensi</th>
                    <th>Catatan Jurnal</th>
                    <!-- Tambahkan kolom lain sesuai kebutuhan -->
                </tr>
            </thead>
            <tbody style="background-color: #EAF5FF;">
                <?php
                $counter = 1; // Inisialisasi counter
                foreach ($absensiList as $absensi) {
                ?>
                    <tr>
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo $absensi['hari']; ?></td>
                        <td><?php echo $absensi['nama_ekstra']; ?></td>
                        <td><?php echo $absensi['batas_absensi']; ?></td>
                        <td><?php echo $absensi['catatan_jurnal']; ?></td>
                        <!-- Tambahkan kolom lain sesuai kebutuhan -->
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <button class="btn btn-success btn-print" onclick="window.print()">Cetak Report</button>
    </div>
    </div>
    </div>
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>
</body>

</html>