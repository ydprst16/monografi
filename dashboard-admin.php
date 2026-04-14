<?php
session_start();

include 'conn.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT kelurahan, kecamatan, kota, provinsi, kode_pos, kode_kemendagri FROM wilayah ORDER BY kelurahan ASC");


?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <!-- Bootstrap 4 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <!-- AdminLTE -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="#" class="nav-link" onclick="openLogoutModal()"><i class="fas fa-sign-out-alt"></i>
                        Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="dashboard-admin.php" class="brand-link">
                <img src="images/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8" />
                <span class="brand-text font-weight-light">Monografi</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="dashboard-admin.php" class="nav-link active">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Monografi Kelurahan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola-operator.php" class="nav-link">
                                <i class="nav-icon fas fa-users-cog"></i>
                                <p>Kelola Operator</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="log-aktivitas.php" class="nav-link">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Log Aktivitas</p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <section class="content-header">
                <div class="container-fluid">
                    <h1>Dashboard Admin</h1>
                </div>
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">

                    <!-- Search bar -->
                    <div class="input-group mb-3">
                        <input type="text" id="searchInput" onkeyup="searchKelurahan()" class="form-control"
                            placeholder="Cari kelurahan...">
                        <div class="input-group-append">
                            <button class="btn btn-primary" onclick="searchKelurahan()">Cari</button>
                        </div>
                    </div>

                    <h3>Daftar Kelurahan</h3>
                    <div id="kelurahanList" class="row">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="col-md-2 mb-2">
                                <div class="card h-100">
                                    <div class="card-body d-flex flex-column">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-map-marker-alt fa-2x text-primary mr-2"></i>
                                            <h5 class="card-title mb-0 flex-grow-1">
                                                <?php echo htmlspecialchars($row['kelurahan']); ?>
                                            </h5>
                                        </div>
                                        <div class="mt-auto">
                                            <a href="input-data.php?<?php echo http_build_query([
                                                'kelurahan' => $row['kelurahan'],
                                                'kecamatan' => $row['kecamatan'],
                                                'kota' => $row['kota'],
                                                'provinsi' => $row['provinsi'],
                                                'kode_pos' => $row['kode_pos'],
                                                'kode_kemendagri' => $row['kode_kemendagri']
                                            ]); ?>" class="btn btn-sm btn-info btn-block">Edit</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </section>
        </div>

        <!-- Footer -->
        <footer class="main-footer">
            <div class="float-right d-none d-sm-inline">Admin Dashboard</div>
            <strong>&copy; 2025 IPDS BPS KOTA DUMAI.</strong>
        </footer>
    </div>

    <!-- Logout Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">Konfirmasi Logout</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Apakah Anda yakin ingin keluar?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <a href="logout.php" class="btn btn-danger">Ya, Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function searchKelurahan() {
            const input = document.getElementById('searchInput');
            const filter = input.value.toLowerCase();
            const cards = document.querySelectorAll('#kelurahanList .card');

            cards.forEach(card => {
                const kelurahanName = card.querySelector('.card-title').textContent.toLowerCase();
                card.style.display = kelurahanName.includes(filter) ? '' : 'none';
            });
        }

        function openLogoutModal() {
            $('#logoutModal').modal('show');
        }
    </script>

    <?php if (isset($_SESSION['notif'])): ?>
        <script>
            Swal.fire({
                icon: '<?= $_SESSION['notif'] ?>',
                title: '<?= $_SESSION['notif'] == "success" ? "Berhasil!" : "Gagal!" ?>'
            });
        </script>
        <?php unset($_SESSION['notif']); ?>
    <?php endif; ?>

</body>

</html>