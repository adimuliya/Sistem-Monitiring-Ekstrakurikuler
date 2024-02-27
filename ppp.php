 <?php
    session_start();
    include 'koneksi.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['username']) && isset($_POST['password']) && isset($_POST['role'])) {
            $username = trim($_POST['username']);
            $password = $_POST['password'];
            $role = trim($_POST['role']);

            // Query untuk mengambil data pengguna dari database
            $query = "SELECT * FROM users WHERE username = '$username' AND password = '$password' AND role = '$role'";
            $result = mysqli_query($conn, $query);

            // Tampilkan pesan kesalahan jika query gagal
            if (!$result) {
                die("Query failed: " . mysqli_error($conn));
            }

            // var_dump(mysqli_num_rows($result));

            // Periksa hasil dari query
            if (mysqli_num_rows($result) > 0) {
                // Login berhasil
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Redirect ke halaman sesuai peran
                switch ($role) {
                    case 'siswa':
                        header('Location: siswa/siswa.php');
                        exit();
                    case 'pembimbing':
                        header('Location: pembimbing_dashboard.php');
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
            echo
            '
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
    $(document).ready(function () {
        $("#errorModal").modal("show");

        $("#errorModal").on("hidden.bs.modal", function () {
            // Bersihkan isian formulir jika diperlukan
            $("#exampleInputUsername").val("");
            $("#exampleInputPassword").val("");
            $("#roleSelect").val("Select");
        });
    });
</script>';
        }
    }
    ?>