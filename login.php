 <?php
    session_start();
    include 'koneksi.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['username']) && isset($_POST['password'])) {
            $username = trim($_POST['username']);
            $password = $_POST['password'];

            // Query untuk mengambil data pengguna dari database
            $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
            $result = mysqli_query($conn, $query);

            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            if (mysqli_num_rows($result) > 0) {
                // Login berhasil
                $user = mysqli_fetch_assoc($result);
                $_SESSION['id_user'] = $user['id_user'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
               

                // Query tambahan untuk mengambil data sesuai dengan role tertentu (misalnya, siswa)
                if ($user['role'] == 'siswa') {
                    $query_siswa = "SELECT * FROM tb_siswa WHERE id_user = " . $user['id_user'];
                    $result_siswa = mysqli_query($conn, $query_siswa);
                    if ($result_siswa && mysqli_num_rows($result_siswa) > 0) {
                        $siswa_data = mysqli_fetch_assoc($result_siswa);
                        $_SESSION['id_user'] = $user['id_user'];
                        $_SESSION['id_siswa'] = $siswa_data['id_siswa']; 
                        $_SESSION['nama_siswa'] = $siswa_data['nama_siswa'];
                        $_SESSION['nis'] = $siswa_data['nis'];
                        $_SESSION['kelas'] = $siswa_data['kelas'];
                    } else {
                        // Data siswa tidak ditemukan, mungkin perlu ditangani sesuai kebutuhan
                    }
                }

                // Redirect ke halaman sesuai peran
                switch ($user['role']) {
                    case 'siswa':
                        header('Location: siswa/siswa.php');
                        exit();
                    case 'pembimbing':
                        header('Location: pembimbing/pembimbing.php');
                        exit();
                    case 'kesiswaan':
                        header('Location: kesiswaan/kesiswaan.php');
                        exit();
                }
            } else {
                // Login gagal, tampilkan modal kesalahan
                echo '
            <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="errorModalLabel">Login Error</h5>
                            <button type="button" id="closeModal" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            Username, password, atau role tidak valid. Silakan coba lagi.
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>';
            }
        } else {
            // Formulir tidak lengkap
            // Tampilkan modal kesalahan formulir tidak lengkap
            echo '
        <div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="errorModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="errorModalLabel">Form Incomplete</h5>
                        <button type="button" id="closeModal" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Formulir login tidak lengkap. Harap isi semua field.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <script>
            // Tambahkan script untuk menyembunyikan modal saat ditutup

            // Tambahkan script untuk menyembunyikan modal saat ditutup
            $(document).ready(function () {
                $("#errorModal").modal("show");

                $("#errorModal").on("hidden.bs.modal", function () {
                    // Bersihkan isian formulir jika diperlukan
                    $("#exampleInputUsername").val("");
                    $("#exampleInputPassword").val("");
                });
            });
        </script>';
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
     <!-- jQuery -->
     <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
     <!-- Bootstrap JavaScript (popper.js) -->
     <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

     <title>Sistem Monitoring Ekstrakurikuler - Login</title>

     <!-- Custom fonts for this template-->
     <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
     <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

     <!-- Custom styles for this template-->
     <link href="css/sb-admin-2.min.css" rel="stylesheet">



 </head>

 <body class="bg-gradient-primary">

     <div class="container">

         <!-- Outer Row -->
         <div class="row justify-content-center">

             <div class="col-xl-10 col-lg-12 col-md-9">

                 <div class="card o-hidden border-0 shadow-lg my-5">
                     <div class="card-body p-0">
                         <!-- Nested Row within Card Body -->
                         <div class="row">
                             <div class="col-lg-6 d-none d-lg-block text-center">
                                <img src="img/login.jpg" style="width: 325px;" alt="">
                             </div>
                             <div class="col-lg-6">
                                 <div class="p-5">
                                     <div class="text-center">
                                         <h1 class="h4 text-gray-900 mb-4">Selamat Datang di Login !</h1>
                                     </div>
                                     <form class="user" method="post" action="login.php">
                                         <div class="form-group">
                                             <input type="text" class="form-control form-control-user" id="exampleInputUsername" aria-describedby="usernameHelp" placeholder="Masukkan Username" name="username">
                                         </div>

                                         <div class="form-group">
                                             <input type="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Masukkan Password" name="password">
                                         </div>
                                         <button type="submit" class="btn btn-primary btn-user btn-block">Login</button>

                                     </form>

                                 </div>
                             </div>
                         </div>
                     </div>
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

     <script>
         $(document).ready(function() {
             $("#errorModal").modal("show");
         });

         $(document).on("click", '#closeModal', function() {
             $("#errorModal").modal("hide");
         });

         // Menggunakan event handler untuk menutup modal dan membersihkan formulir
         $("#errorModal").on("hidden.bs.modal", function() {
             // Bersihkan isian formulir jika diperlukan
             $("#exampleInputUsername").val("");
             $("#exampleInputPassword").val("");
         });
     </script>





 </body>

 </html>