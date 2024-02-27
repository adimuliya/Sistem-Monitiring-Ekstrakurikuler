<?php
session_start();
include 'koneksi.php';

// Periksa apakah pengguna sudah login dan memiliki rolenya
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'kesiswaan') {
    header('Location: ../login.php'); // Ganti login.php dengan halaman login dan sesuaikan dengan struktur folder
    exit();
}

// Lakukan operasi atau tampilkan konten sesuai dengan peran (role) pengguna
$role = $_SESSION['role'];

// ... (aturan bisnis atau tampilan sesuai dengan role) ...

// Mendapatkan jumlah keseluruhan ekstrakurikuler dari database (misalnya dengan query SELECT COUNT(*) FROM ekstrakurikuler)
$totalEkstraQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM tb_ekstrakurikuler");
$totalEkstra = mysqli_fetch_assoc($totalEkstraQuery)['total'];

$totalAkunQuery = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$totalAkun = mysqli_fetch_assoc($totalAkunQuery)['total'];

$totalEkstraIkutQuery = mysqli_query($conn, "SELECT COUNT(DISTINCT id_siswa) AS total FROM tb_ekstra_yang_diikuti");
$totalEkstraIkut = mysqli_fetch_assoc($totalEkstraIkutQuery)['total'];





// Menyesuaikan warna card (contoh: primary untuk total ekstrakurikuler)
$cardColorClass = "border-left-primary";

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Sistem Monitoring Ekstrakurikuler | Dashboard</title>

    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
    <!-- Memastikan jQuery dimuat sebelum script Anda -->
    <script>
        $(document).ready(function() {
            <?php
            // Periksa apakah ada pesan hasil
            if (isset($_SESSION['result'])) {
                // Tambahkan kondisi untuk menentukan warna modal berdasarkan hasil
                $modalClass = ($_SESSION['result'] == "Data berhasil dimasukkan!") ? "alert-success" : "alert-danger";

                // Output JavaScript untuk menampilkan alert dengan warna yang sesuai
                echo "alert('" . $_SESSION['result'] . "');";

                // Unset variabel sesi untuk mencegah tampilnya pesan pada reload halaman berikutnya
                unset($_SESSION['result']);
            }
            ?>
        });
    </script>



</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
                <div class="sidebar-brand-icon rotate-n-15">

                </div>
                <div class="sidebar-brand-text mx-1">Sistem Monitoring Ekstrakurikuler</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item">
                <a class="nav-link" href="kesiswaan.php">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Interface
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link" href="tambah_ekstra.php">
                    <i class="fas fa-volleyball-ball "></i>
                    <span>Ekstrakurikuler</span></a>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->


            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item active">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-users fa-2x"></i></i>
                    <span>Akun</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="buat_akun.php">Buat Akun Kesiswaan</a>
                        <a class="collapse-item" href="buat_akun_pembimbing.php">Buat Akun Pembimbing</a>
                        <a class="collapse-item" href="buat_akun_siswa.php">Buat Akun Siswa</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="laporan_jurnal.php">
                    <i class="fas fa-volleyball-ball "></i>
                    <span>Laporan Jurnal</span></a>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>


        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>
                    <div class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 ">
                        <h1 class="h3 mb-0 text-gray-800">Akun</h1>
                    </div>

                    <!-- Topbar Search 
                    <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search">
                        <div class="input-group">
                            <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                            <div class="input-group-append">
                                <button class="btn btn-primary" type="button">
                                    <i class="fas fa-search fa-sm"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                    -->



                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small" placeholder="Search for..." aria-label="Search" aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>




                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-user fa-sm fa-fw mr-2"></i> <!-- Ikon Pengguna -->
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['username']; ?> | <?php echo $_SESSION['role']; ?></span>
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                                <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 y-400 text"></i>
                                    Logout
                                </a>
                            </div>
                        </li>
                    </ul>
                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Tabel Daftar Akun dengan Efek Bayangan -->
                    <div class="container">
                        <div class="card shadow mb-4">
                            <div class="card-header py-3">
                                <div class="d-sm-flex align-items-center justify-content-between mb-1">
                                    <h6 class="m-0 font-weight-bold text-primary">Data Akun Pembimbing</h6>
                                    <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-danger shadow-sm" data-toggle="modal" data-target="#tambahEkstraModal">
                                        <i class="fas fa-plus fa-sm text-light-50"></i> Tambah Akun
                                    </a>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                        <thead class="bg-primary text-light">
                                            <tr>
                                                <th>No</th>
                                                <th>Username</th>
                                                <th>Role</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody style="background-color: #EAF5FF;">
                                            <?php
                                            // Query untuk mendapatkan data pengguna dengan role pembimbing
                                            $usersQuery = mysqli_query($conn, "SELECT username, role FROM users WHERE role = 'pembimbing'");

                                            // Variabel untuk nomor otomatis
                                            $nomor = 1;

                                            // Loop untuk menampilkan data pengguna
                                            while ($user = mysqli_fetch_assoc($usersQuery)) {
                                                echo "<tr>";
                                                echo "<td>{$nomor}</td>";
                                                echo "<td>{$user['username']}</td>";
                                                echo "<td>{$user['role']}</td>";
                                                echo "<td>
                    <button class='btn btn-warning btn-sm' data-toggle='modal' data-target='#editAkunModal{$nomor}'>Edit</button>
                     <button class='btn btn-danger btn-sm' onclick=\"hapusAkunModal({$nomor}, '{$user['username']}')\">Hapus</button>";
                                                echo "</tr>";

                                                // Increment nomor
                                                $nomor++;
                                            }
                                            ?>


                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $nomor = 1;
                    $usersQuery = mysqli_query($conn, "SELECT username, role FROM users WHERE role = 'pembimbing'");
                    while ($user = mysqli_fetch_assoc($usersQuery)) {
                    ?>
                        <!-- Modal konfirmasi hapus -->
                        <div class="modal fade" id="hapusAkunModal<?php echo $nomor; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class='modal-dialog' role='document'>
                                <div class='modal-content'>
                                    <div class='modal-header'>
                                        <h5 class='modal-title text-danger' id='exampleModalLabel'>Konfirmasi Hapus Akun</h5>
                                        <button class='close' type='button' data-dismiss='modal' aria-label='Close'>
                                            <span aria-hidden='true'>&times;</span>
                                        </button>
                                    </div>
                                    <div class='modal-body'>
                                        Apakah Anda yakin ingin menghapus akun ini?
                                    </div>
                                    <div class='modal-footer'>
                                        <button class="btn btn-sm" type="button" data-dismiss="modal">Batal</button>
                                        <button class='btn btn-danger btn-sm' id='hapusAkunBtn'>Hapus</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                    <?php
                        $nomor++;
                    }
                    ?>


                    <?php
                    // Loop untuk menampilkan modal edit untuk setiap akun pembimbing
                    $nomor = 1;
                    $usersQuery = mysqli_query($conn, "SELECT username, role FROM users WHERE role = 'pembimbing'");
                    while ($user = mysqli_fetch_assoc($usersQuery)) {
                    ?>
                        <!-- Modal Edit Akun Pembimbing -->
                        <div class="modal fade" id="editAkunModal<?php echo $nomor; ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title text-primary" id="exampleModalLabel">Edit Akun</h5>
                                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <!-- Form untuk mengedit username -->
                                        <form method="post" action="proses_edit_akun.php">
                                            <div class="form-group">
                                                <label for="new_username">Username Baru:</label>
                                                <input type="text" class="form-control" id="new_username" name="new_username" value="<?php echo $user['username']; ?>">
                                            </div>

                                            <!-- Tambahkan input hidden untuk menyimpan username lama -->
                                            <input type="hidden" name="old_username" value="<?php echo $user['username']; ?>">

                                            <!-- Tambahkan kolom untuk pemilihan role -->
                                            <div class="form-group">
                                                <label for="role">Role:</label>
                                                <select class="form-select form-control" aria-label="Default select example" id="role" name="role">
                                                    <option value="pembimbing" selected>Pembimbing</option>
                                                </select>
                                            </div>

                                            <!-- Tambahkan button submit pada akhir formulir -->
                                            <button type="submit" class="btn btn-primary" name="submit">Simpan Perubahan</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php
                        $nomor++;
                    }
                    ?>


                    <!-- Modal Buat Akun -->
                    <div class="modal fade" id="tambahEkstraModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-primary" id="exampleModalLabel">Buat Akun</h5>
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">×</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form method="post" action="proses_tambah_akun_pembimbing.php">
                                        <div class="form-group">
                                            <label for="username">Username :</label>
                                            <input type="text" class="form-control" id="username" name="username" placeholder="Username" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Password:</label>
                                            <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="role">Role:</label>
                                            <select class="form-select form-control" aria-label="Default select example" id="role" name="role">
                                                <option selected>Role</option>
                                                <option value="pembimbing">pembimbing</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#resultModal" name="submit">Simpan</button>

                                    </form>
                                </div>
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


                    <script>
                        $(document).ready(function() {
                            $('#logoutModal').on('hidden.bs.modal', function() {

                            });
                        });
                    </script>








                    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

                    <script>
                        function hapusAkunModal(nomor, username) {
                            // Tampilkan modal konfirmasi hapus dengan ID yang sesuai
                            $(`#hapusAkunModal${nomor}`).modal('show');

                            // Set nilai atribut data-username pada tombol 'Hapus' di modal
                            $('#hapusAkunBtn').attr('data-username', username);
                        }

                        // Fungsi untuk menangani penghapusan akun
                        $('#hapusAkunBtn').click(function() {
                            // Ambil nilai username dari atribut data-username
                            var username = $(this).data('username');

                            // Redirect atau jalankan proses hapus
                            window.location.href = "proses_hapus_akun_pembimbing.php?username=" + username;
                        });
                    </script>


                    <!-- Bootstrap core JavaScript-->
                    <script src="vendor/jquery/jquery.min.js"></script>
                    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

                    <!-- Core plugin JavaScript-->
                    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>

                    <!-- Custom scripts for all pages-->
                    <script src="js/sb-admin-2.min.js"></script>

                    <!-- Page level plugins -->
                    <script src="vendor/chart.js/Chart.min.js"></script>

                    <!-- Page level custom scripts -->
                    <script src="js/demo/chart-area-demo.js"></script>
                    <script src="js/demo/chart-pie-demo.js"></script>

</body>

</html>