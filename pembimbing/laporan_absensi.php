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

$laporanAbsensiList = mysqli_fetch_all($queryAbsensi, MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title id="pageTitle">Sistem Monitoring Ekstrakurikuler | Dashboard</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
        });

        $(document).ready(function() {
            var startDate = ''; // Variabel untuk menyimpan tanggal mulai
            var endDate = ''; // Variabel untuk menyimpan tanggal selesai

            $('#tanggalMulai, #tanggalSelesai').change(function() {
                // Setelah kedua tanggal dipilih, filter data
                startDate = $('#tanggalMulai').val();
                endDate = $('#tanggalSelesai').val();

                if (startDate !== '' && endDate !== '') {
                    updateUrlWithDateFilter();
                }
            });

            function updateUrlWithDateFilter() {
                // Filter data sesuai dengan tanggal
                var newUrl = updateQueryStringParameter(window.location.href, 'start_date', startDate);
                newUrl = updateQueryStringParameter(newUrl, 'end_date', endDate);

                // Redirect ke URL yang baru
                window.location.href = newUrl;
                updateDataTable();
            }



            function updateQueryStringParameter(uri, key, value) {
                var re = new RegExp("([?&])" + key + "=.*?(&|$)", "i");
                var separator = uri.indexOf('?') !== -1 ? "&" : "?";
                if (uri.match(re)) {
                    return uri.replace(re, '$1' + key + "=" + value + '$2');
                } else {
                    return uri + separator + key + "=" + value;
                }
            }
        });
    </script>
    <!-- Ubah script jQuery untuk menangani klik tombol edit -->
    <style>
        @media print {
            body * {
                display: none;
            }

            #content-wrapper,
            #content-wrapper * {
                display: block !important;
            }

            #dataTable,
            #dataTable * {
                visibility: visible;
            }

            #dataTable {
                margin: auto;
            }
        }
    </style>
</head>

<body id="page-top">
    <div id="wrapper" class="wrap">
        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion sidebar-wrapper" id="accordionSidebar"">
            <!-- Sidebar - Brand -->
            <a class=" sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
            <div class="sidebar-brand-icon rotate-n-15"></div>
            <div class="sidebar-brand-text mx-1">Sistem Monitoring Ekstrakurikuler</div>
            </a>
            <hr class="sidebar-divider my-0">
            <li class="nav-item">
                <a class="nav-link" href="pembimbing.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Interface</div>
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages2" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-users-cog fa-2x"></i>
                    <span>Absensi</span>
                </a>
                <div id="collapsePages2" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="buat_absensi_siswa.php">Buat Absensi</a>
                        <a class="collapse-item" href="cek_absensi.php">Cek Absensi</a>
                    </div>
                </div>
            </li>
            <hr class="sidebar-divider">
            <div class="sidebar-heading">Addons</div>
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-server fa-2x"></i>
                    <span>Laporan</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="laporan_absensi.php">Laporan Absensi</a>
                        <a class="collapse-item" href="laporan_jurnal">Laporan Jurnal</a>
                    </div>
                </div>
            </li>
        </ul>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 ">
                        <h1 class="h3 mb-0 text-gray-800">Laporan Absensi Jurnal</h1>
                    </div>
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user fa-sm fa-fw mr-2"></i>
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['username']; ?> | <?php echo $_SESSION['role']; ?></span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 y-400 text"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <div class="container-fluid">
                    <div class="container-fluid">
                        <!-- Modal Edit Absensi -->
                        <div class="modal fade" id="editAbsensiModal" tabindex="-1" role="dialog" aria-labelledby="editAbsensiModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editAbsensiModalLabel">Edit Absensi</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form untuk edit absensi -->
                                        <form method="post" action="proses_edit_absensi.php">
                                            <!-- Tambahkan input hidden untuk menyimpan id_absensi_siswa -->
                                            <input type="hidden" id="editAbsensiIdSiswa" name="editAbsensiIdSiswa" value="">

                                            <!-- Tambahkan field edit yang diperlukan sesuai kebutuhan -->
                                            <div class="form-group">
                                                <label for="editJenisAbsensi">Jenis Absensi</label>
                                                <select class="form-control" id="editJenisAbsensi" name="editJenisAbsensi" required>
                                                    <!-- Tambahkan pilihan jenis absensi -->
                                                    <option value="Hadir">Hadir</option>
                                                    <option value="Sakit">Sakit</option>
                                                    <option value="Izin">Izin</option>
                                                    <!-- Tambahkan opsi lainnya sesuai kebutuhan -->
                                                </select>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card untuk menampilkan data laporan absensi -->
                        <div class="card shadow mb-4">
                            <div class="card-body">
                                <div class="card-header py-3">
                                    <div class="d-sm-flex align-items-center justify-content-between mb-1">
                                        <h6 class="m-0 font-weight-bold text-primary">Laporan Absensi</h6>
                                    </div>
                                    <div class="form-row">
                                        <!-- Tambahkan di dalam tag <div class="form-row"> -->
                                        <div class="col mb-2">
                                            <label for="tanggalMulai">Tanggal Mulai:</label>
                                            <input type="date" class="form-control" id="tanggalMulai" name="tanggalMulai" value="<?php echo $tanggalMulai; ?>">
                                        </div>
                                        <div class="col mb-2">
                                            <label for="tanggalSelesai">Tanggal Selesai:</label>
                                            <input type="date" class="form-control" id="tanggalSelesai" name="tanggalSelesai" value="<?php echo $tanggalSelesai; ?>">
                                        </div>

                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered table-a" id="dataTable" width="100%" cellspacing="0">
                                            <thead class="bg-primary text-light">
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
                                            <tbody style="background-color: #EAF5FF;">
                                                <?php
                                                $counter = 1; // Inisialisasi counter
                                                foreach ($laporanAbsensiList as $laporanAbsensi) {
                                                ?>
                                                    <tr>
                                                        <td><?php echo $counter++; ?></td>
                                                        <td><?php echo $laporanAbsensi['nama_siswa']; ?></td>
                                                        <td><?php echo $laporanAbsensi['nama_ekstra']; ?></td>
                                                        <td><?php echo $laporanAbsensi['jumlah_hadir']; ?></td>
                                                        <td><?php echo $laporanAbsensi['jumlah_izin']; ?></td>
                                                        <td><?php echo $laporanAbsensi['jumlah_sakit']; ?></td>
                                                        <td><?php echo $laporanAbsensi['jumlah_alpha']; ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <button class="btn btn-success btn-print mt-3" onclick="printReport()">
                                    <i class="fas fa-print"></i> Cetak Report
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- Logout Modal -->
                    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="logoutModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="logoutModalLabel">Logout Confirmation</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">Are you sure you want to logout?</div>
                                <div class="modal-footer">
                                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                    <a class="btn btn-primary" href="../logout.php">Logout</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bootstrap core JavaScript-->
                    <script src="vendor/jquery/jquery.min.js"></script>
                    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                    <!-- Core plugin JavaScript-->
                    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

                    <!-- Custom scripts for all pages-->
                    <script src="js/sb-admin-2.min.js"></script>

                    <!-- Page level plugins -->
                    <script src="vendor/datatables/jquery.dataTables.min.js"></script>
                    <script src="vendor/datatables/dataTables.bootstrap4.min.js"></script>

                    <!-- Page level custom scripts -->
                    <script src="js/demo/datatables-demo.js"></script>
                    <script>
                        $(document).ready(function() {
                            $('.btn-edit').click(function() {
                                var idSiswa = $(this).data('absensi-siswa-id');
                                $('#editAbsensiIdSiswa').val(idSiswa);

                                var jenisAbsensi = $(this).closest('tr').find('td:eq(9)').text();
                                $('#editJenisAbsensi').val(jenisAbsensi);

                                $('#editAbsensiModal').modal('show');
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
        <script>
            function handlePrint() {
                window.print(); // Memanggil fungsi cetak bawaan browser
            }

            function printReport() {
                // Dapatkan tanggalMulai dan tanggalSelesai dari URL
                var startDate = getParameterByName('start_date');
                var endDate = getParameterByName('end_date');

                // Redirect ke halaman cetak dengan parameter tanggal
                window.location.href = 'cetak_laporan_absensi.php?start_date=' + startDate + '&end_date=' + endDate;
            }

            // Fungsi untuk mendapatkan nilai parameter dari URL
            function getParameterByName(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, '\\$&');
                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            }
            $(document).ready(function() {
                $('.btn-edit').click(function() {
                    var idSiswa = $(this).data('absensi-siswa-id');
                    $('#editAbsensiIdSiswa').val(idSiswa);

                    var jenisAbsensi = $(this).closest('tr').find('td:eq(9)').text();
                    $('#editJenisAbsensi').val(jenisAbsensi);

                    $('#editAbsensiModal').modal('show');
                });
            });
        </script>
        <!-- Pastikan jQuery sudah disertakan sebelum script ini -->
</body>

</html>