<?php
session_start();
include 'koneksi.php';

                                                // Periksa apakah pengguna sudah login dan memiliki rolenya
                                                if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'siswa') {
                                                    header('Location: ../login.php'); // Ganti login.php dengan halaman login dan sesuaikan dengan struktur folder
                                                    exit();
                                                }


// Ambil informasi pengguna terautentikasi
$id_user = $_SESSION['id_user'];
$nama_siswa = $_SESSION['nama_siswa'];
$nis = $_SESSION['nis'];
$kelas = $_SESSION['kelas'];

// Cek apakah data siswa tersedia
if (empty($id_user) || empty($nama_siswa) || empty($nis) || empty($kelas)) {
    // Data siswa tidak ditemukan, mungkin perlu ditangani sesuai kebutuhan
    echo "Data siswa tidak ditemukan. Silakan hubungi administrator.";
    exit();
}

// Jika tombol daftar ditekan
if (isset($_POST['submit'])) {
    // Tangkap data dari form
    $id_ekstra = $_POST['id_ekstra'];

    // Query untuk mendapatkan id_siswa dari tb_siswa berdasarkan nis
    $query_get_id_siswa = "SELECT id_siswa FROM tb_siswa WHERE nis = '$nis'";
    $result_get_id_siswa = mysqli_query($conn, $query_get_id_siswa);

    if ($result_get_id_siswa) {
        $row = mysqli_fetch_assoc($result_get_id_siswa);
        $id_siswa = $row['id_siswa'];

        // Query untuk menghitung jumlah ekstrakurikuler yang sudah didaftarkan oleh siswa
        $query_count_ekstra = "SELECT COUNT(*) AS total FROM tb_ekstra_yang_diikuti WHERE id_siswa = '$id_siswa'";
        $result_count_ekstra = mysqli_query($conn, $query_count_ekstra);

        if ($result_count_ekstra) {
            $total_ekstra_pendaftaran = mysqli_fetch_assoc($result_count_ekstra)['total'];

            // Periksa apakah jumlah ekstrakurikuler yang didaftarkan sudah mencapai batas (3)
            if ($total_ekstra_pendaftaran >= 3) {
                echo "<script>alert('Maaf, Anda sudah mendaftarkan 3 ekstrakurikuler. Tidak bisa mendaftar lebih banyak.'); window.location.href='siswa.php';</script>";
                exit(); // Keluar dari skrip jika sudah mencapai batas
            }

            // Periksa apakah ekstrakurikuler yang dipilih sudah didaftarkan sebelumnya
            $query_check_duplicate = "SELECT * FROM tb_ekstra_yang_diikuti WHERE id_siswa = '$id_siswa' AND id_ekstra = '$id_ekstra'";
            $result_check_duplicate = mysqli_query($conn, $query_check_duplicate);

            if ($result_check_duplicate && mysqli_num_rows($result_check_duplicate) > 0) {
                echo "<script>alert('Maaf, Anda sudah mendaftar ekstrakurikuler ini sebelumnya.'); window.location.href='siswa.php';</script>";
                exit(); // Keluar dari skrip jika sudah mendaftar ekstrakurikuler yang sama
            }
        } else {
            echo "<script>alert('Gagal menghitung jumlah ekstrakurikuler: " . mysqli_error($conn) . "'); window.location.href='form_pendaftaran.php';</script>";
            exit(); // Keluar dari skrip jika terjadi kesalahan
        }

        // Periksa lagi sebelum memproses pendaftaran
        if ($total_ekstra_pendaftaran < 3) {
            // Query untuk mendapatkan nama ekstra dari tb_ekstrakurikuler berdasarkan id_ekstra
            $query_get_ekstra_info = "SELECT nama_ekstra FROM tb_ekstrakurikuler WHERE id_ekstra = '$id_ekstra'";
            $result_get_ekstra_info = mysqli_query($conn, $query_get_ekstra_info);

            if ($result_get_ekstra_info) {
                // Periksa apakah baris data ditemukan
                if (mysqli_num_rows($result_get_ekstra_info) > 0) {
                    $ekstra_info = mysqli_fetch_assoc($result_get_ekstra_info);
                    $nama_ekstra = $ekstra_info['nama_ekstra'];

                    // Lakukan proses pendaftaran (simpan ke tb_ekstra_yang_diikuti)
                    // Gunakan id_siswa yang didapatkan dari tb_siswa
                    $query_pendaftaran = "INSERT INTO tb_ekstra_yang_diikuti (id_user, id_siswa, id_ekstra) VALUES ('$id_user', '$id_siswa', '$id_ekstra')";
                    $result_pendaftaran = mysqli_query($conn, $query_pendaftaran);

                    // Handle kesuksesan atau kegagalan pendaftaran
                    if ($result_pendaftaran) {
                        echo "<script>alert('Pendaftaran sukses!');  window.location.href='siswa.php';</script>";
                    } else {
                        echo "<script>alert('Pendaftaran gagal: " . mysqli_error($conn) . "');  window.location.href='siswa.php';</script>";
                    }
                } else {
                    echo "<script>alert('Data ekstra tidak ditemukan.');  window.location.href='siswa.php';</script>";
                }
            } else {
                echo "<script>alert('Gagal mendapatkan informasi ekstra: " . mysqli_error($conn) . "');  window.location.href='siswa.php';</script>";
            }
        }
    } else {
        echo "<script>alert('Gagal mendapatkan id_siswa: " . mysqli_error($conn) . "'); window.location.href='siswa.php';</script>";
    }
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
    <title>Form Pendaftaran Ekstrakurikuler</title>
    <!-- Custom fonts for this template-->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="css/sb-admin-2.min.css" rel="stylesheet">
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
            <li class="nav-item active">
                <a class="nav-link" href="siswa.php">
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
                <a class="nav-link" href="ekstra_yang_diikuti.php">
                    <i class="fas fa-volleyball-ball"></i>
                    <span>Ekstra Yang Diikuti</span></a>
            </li>

            <!-- Nav Item - Utilities Collapse Menu -->

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Addons
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages" aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-folder"></i>
                    <span>Akun</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="buat_akun.php">Buat Akun</a>
                        <a class="collapse-item" href="register.html">Daftar AKun</a>
                    </div>
                </div>
            </li>

            <!-- Nav Item - Charts -->
            <li class="nav-item">
                <a class="nav-link" href="charts.html">
                    <i class="fas fa-fw fa-chart-area"></i>
                    <span>Charts</span></a>
            </li>

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="tables.html">
                    <i class="fas fa-fw fa-table"></i>
                    <span>Tables</span></a>
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
                        <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
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

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <h6 class="m-0 font-weight-bold text-primary">Form Pendaftaran Ekstrakurikuler</h6>
                                </div>
                                <div class="card-body">
                                    <!-- Your Form Here -->
                                    <form action="" method="POST">
                                        <input type="hidden" name="id_user" value="<?php echo $id_user; ?>">
                                        <input type="hidden" name="id_siswa" value="<?php echo $nis; ?>">
                                        <div class="form-group">
                                            <label for="nama_siswa">Nama Siswa:</label>
                                            <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="<?php echo $nama_siswa; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="nis">NIS:</label>
                                            <input type="text" class="form-control" id="nis" name="nis" value="<?php echo $nis; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="kelas">Kelas:</label>
                                            <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo $kelas; ?>" readonly>
                                        </div>
                                        <div class="form-group">
                                            <label for="id_ekstra">Pilih Ekstrakurikuler:</label>
                                            <select class="form-control" id="id_ekstra" name="id_ekstra">
                                                <?php
                                                $query_ekstra = mysqli_query($conn, "SELECT * FROM tb_ekstrakurikuler");
                                                while ($ekstrakurikuler = mysqli_fetch_assoc($query_ekstra)) {
                                                    echo "<option value='" . $ekstrakurikuler['id_ekstra'] . "'>" . $ekstrakurikuler['nama_ekstra'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="submit" name="submit" class="btn btn-primary">Daftar</button>
                                    </form>
                                    <!-- End of Your Form -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="text-center">
                        <span>Your Footer Information Here</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Apakah kamu yakin akan keluar?</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">batal</button>
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

</body>

</html>