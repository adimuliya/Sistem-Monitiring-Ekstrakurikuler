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
// Periksa apakah data tanggal sudah dikirimkan dari formulir filter
$tanggalMulai = isset($_GET['start_date']) ? $_GET['start_date'] : '';
$tanggalSelesai = isset($_GET['end_date']) ? $_GET['end_date'] : '';
$queryAbsensi = mysqli_query($conn, "SELECT 
                        tb_siswa.nama_siswa, 
                        tb_ekstrakurikuler.nama_ekstra,
                        SUM(CASE WHEN tb_absensi_siswa.jenis_absensi = 'Hadir' THEN 1 ELSE 0 END) AS jumlah_hadir,
                        SUM(CASE WHEN tb_absensi_siswa.jenis_absensi = 'Izin' THEN 1 ELSE 0 END) AS jumlah_izin,
                        SUM(CASE WHEN tb_absensi_siswa.jenis_absensi = 'Sakit' THEN 1 ELSE 0 END) AS jumlah_sakit,
                        SUM(CASE WHEN tb_absensi_siswa.jenis_absensi = 'Alpha' THEN 1 ELSE 0 END) AS jumlah_alpha
                    FROM tb_absensi
                    LEFT JOIN tb_ekstrakurikuler ON tb_absensi.id_ekstra = tb_ekstrakurikuler.id_ekstra
                    LEFT JOIN tb_siswa AS siswa_ekstra ON tb_ekstrakurikuler.id_user = siswa_ekstra.id_user
                    LEFT JOIN tb_absensi_siswa ON tb_absensi.id_absensi = tb_absensi_siswa.id_absensi
                    LEFT JOIN tb_siswa ON tb_absensi_siswa.id_user = tb_siswa.id_user
                    WHERE tb_absensi.hari BETWEEN '$tanggalMulai' AND '$tanggalSelesai'
                    GROUP BY tb_siswa.nama_siswa, tb_ekstrakurikuler.nama_ekstra");

// Cek kesalahan pada query
if (!$queryAbsensi) {
    exit("Error: " . mysqli_error($conn));
}

$absensiList = mysqli_fetch_all($queryAbsensi, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Report Absensi</title>
    <style>
        /* Gaya cetak khusus untuk laporan */
        body {
            font-family: 'Times New Roman', Times, serif;
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
        display: none; /* Sembunyikan kop-surat dan tombol cetak saat dicetak */
    }
}
        
    </style>

</head>

<body>
    <div class="container-fluid">

        <div class="kop-surat">
            <h3>Laporan Jurnal Absensi</h3>
            <div class="header-surat">
                <h1>SMK Negeri 1 Jenangan Ponorogo</h1>
                <p>Jl. Nama Niken Gandini No. 98, Plampitan, Setono, kec. Jenangan Kota, Kabupaten Ponorogo, Jawa Timur, 63492</p>
                <p>Periode: <?php echo $tanggalMulai; ?> sampai <?php echo $tanggalSelesai; ?></p>
            </div>
            <div class="tanggal-surat">
                <p>Tanggal: <?php echo date('d F Y'); ?></p>
            </div>
        </div>
        <hr>


        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Nama Ekstrakurikuler</th>
                    <th>Jumlah Hadir</th>
                    <th>Jumlah Izin</th>
                    <th>Jumlah Sakit</th>
                    <th>Jumlah Alpha</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $counter = 1; // Inisialisasi counter
                foreach ($absensiList as $absensi) {
                ?>
                    <tr>
                        <td><?php echo $counter++; ?></td>
                        <td><?php echo $absensi['nama_siswa']; ?></td>
                        <td><?php echo $absensi['nama_ekstra']; ?></td>
                        <td><?php echo $absensi['jumlah_hadir']; ?></td>
                        <td><?php echo $absensi['jumlah_izin']; ?></td>
                        <td><?php echo $absensi['jumlah_sakit']; ?></td>
                        <td><?php echo $absensi['jumlah_alpha']; ?></td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    <button class="btn btn-success btn-print mt-3" onclick="handlePrint()"><i class="fas fa-print"></i> Cetak Report</button>
    <script>
        function handlePrint() {
            window.print(); // Memanggil fungsi cetak bawaan browser
        }
    </script>
</body>

</html>