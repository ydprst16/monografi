<?php
include 'auth.php';
include 'conn.php';
include 'log.php';

if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard-operator.php");
    exit();
}

$edit_data = null;
$message = '';

// Proses tambah data
if (isset($_POST['tambah'])) {
    $username = $_POST['username'];
    // $password = md5($_POST['password']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $kelurahan = $_POST['kelurahan'];

    $query = "INSERT INTO users (username, password, role, kelurahan) VALUES ('$username', '$password', '$role', '$kelurahan')";
    if (mysqli_query($conn, $query)) {
        $message = "Data berhasil ditambahkan.";
    }
}

// Hapus data
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    $query = "DELETE FROM users WHERE id = $id";
    if (mysqli_query($conn, $query)) {
        $message = "Data berhasil dihapus.";
    }
}

// Ambil data untuk edit
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $query = "SELECT * FROM users WHERE id = $id";
    $result_edit = mysqli_query($conn, $query);
    if ($result_edit) {
        $edit_data = mysqli_fetch_assoc($result_edit);
    }
}

// Update data
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $username = $_POST['username'];
    $role = $_POST['role'];
    $kelurahan = $_POST['kelurahan'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $query = "UPDATE users 
                  SET username='$username', password='$password', role='$role', kelurahan='$kelurahan' 
                  WHERE id=$id";
    } else {

        $query = "UPDATE users 
                  SET username='$username', role='$role', kelurahan='$kelurahan' 
                  WHERE id=$id";
    }

    if (mysqli_query($conn, $query)) {
        $message = "Data berhasil diperbarui.";
        log_activity($conn, $_SESSION['username'], "Update operator " . $username);
        $edit_data = null;
    }
}

//Dropdown Kelurahan
$kelurahan_list = [];
$result_kelurahan = mysqli_query($conn, "SELECT * FROM wilayah ORDER BY kelurahan ASC");
while ($row = mysqli_fetch_assoc($result_kelurahan)) {
    $kelurahan_list[] = $row;
}

// Ambil semua operator
$query = "SELECT * FROM users WHERE role = 'operator'";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Operator</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
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
            <a href="dashboard-admin.php" class="brand-link">
                <img src="images/logo.png" alt="Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="brand-text font-weight-light">Monografi</span>
            </a>
            <div class="sidebar">
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                        <li class="nav-item">
                            <a href="dashboard-admin.php" class="nav-link">
                                <i class="nav-icon fas fa-home"></i>
                                <p>Monografi Kelurahan</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="kelola-operator.php" class="nav-link active">
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
            </div>
        </aside>

        <!-- Content Wrapper -->
        <div class="content-wrapper">
            <!-- Content Header -->
            <div class="content-header">
                <div class="container-fluid">
                    <h1 class="m-0">Kelola Operator</h1>
                </div>
            </div>
            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <?php if ($message): ?>
                        <div class="alert alert-success"><?= $message ?></div>
                    <?php endif; ?>

                    <!-- Form Card -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?php echo $edit_data ? 'Edit Operator' : 'Tambah Operator'; ?></h3>
                        </div>
                        <form action="kelola-operator.php" method="POST">
                            <div class="card-body">
                                <?php if ($edit_data): ?>
                                    <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                                <?php endif; ?>
                                <div class="form-group">
                                    <label for="username">Username</label>
                                    <input type="text" name="username" id="username" class="form-control"
                                        placeholder="Username" value="<?= $edit_data['username'] ?? '' ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" name="password" id="password" class="form-control"
                                        placeholder="Password" required>
                                </div>
                                <div class="form-check mb-3">
                                    <input type="checkbox" id="show-password" class="form-check-input">
                                    <label for="show-password" class="form-check-label">Tampilkan Password</label>
                                </div>
                                <div class="form-group">
                                    <label for="role">Role</label>
                                    <input type="text" name="role" id="role" class="form-control" value="operator"
                                        readonly>
                                </div>
                                <div class="form-group">
                                    <label for="kelurahan">Kelurahan</label>
                                    <select name="kelurahan" id="kelurahan" class="form-control" required>
                                        <option value="">-- Pilih Kelurahan --</option>
                                        <?php foreach ($kelurahan_list as $kel): ?>
                                            <option value="<?= $kel['kelurahan'] ?>" <?= (isset($edit_data['kelurahan']) && $edit_data['kelurahan'] === $kel['kelurahan']) ? 'selected' : '' ?>>
                                                <?= $kel['kelurahan'] ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                            </div>
                            <div class="card-footer">
                                <?php if ($edit_data): ?>
                                    <button type="submit" name="update" class="btn btn-primary"><i class="fas fa-save"></i>
                                        Update Operator</button>
                                <?php else: ?>
                                    <button type="submit" name="tambah" class="btn btn-success"><i
                                            class="fas fa-plus-circle"></i> Tambah Operator</button>
                                <?php endif; ?>
                            </div>
                        </form>
                    </div>

                    <!-- Data Table -->
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Data Operator</h3>
                        </div>
                        <div class="card-body">
                            <table id="operatorTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Username</th>
                                        <th>Kelurahan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                            <td><?= htmlspecialchars($row['kelurahan']) ?></td>
                                            <td>
                                                <a href="?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm"><i
                                                        class="fas fa-edit"></i> Edit</a>
                                                <a href="?hapus=<?= $row['id'] ?>"
                                                    onclick="return confirm('Yakin ingin menghapus?')"
                                                    class="btn btn-danger btn-sm"><i class="fas fa-trash"></i> Hapus</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- Logout Modal -->
        <div class="modal fade" id="logoutModal" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Logout</h5>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah Anda yakin ingin keluar?</p>
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
        <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap4.min.js"></script>

        <script src="https://cdn.datatables.net/buttons/2.3.6/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.bootstrap4.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.3.6/js/buttons.print.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script>
            $(function () {
                $('#operatorTable').DataTable({
                    responsive: true,
                    autoWidth: false,
                    dom: 'Bfrtip',
                    buttons: [
                        {
                            extend: 'csvHtml5',
                            text: '<i class="fas fa-file-csv"></i> Export CSV',
                            className: 'btn btn-success btn-sm',
                            exportOptions: {
                                columns: [0, 1] // Hanya kolom Username dan Kelurahan
                            }
                        },
                        {
                            extend: 'excelHtml5',
                            text: '<i class="fas fa-file-excel"></i> Export Excel',
                            className: 'btn btn-success btn-sm',
                            exportOptions: {
                                columns: [0, 1] // Hanya kolom Username dan Kelurahan
                            }
                        },
                        {
                            extend: 'print',
                            text: '<i class="fas fa-print"></i> Print',
                            className: 'btn btn-info btn-sm',
                            exportOptions: {
                                columns: [0, 1] // Hanya kolom Username dan Kelurahan
                            }
                        }
                    ]
                });

                $('#show-password').on('change', function () {
                    $('#password').attr('type', this.checked ? 'text' : 'password');
                });
            });
            function openLogoutModal() {
                $('#logoutModal').modal('show');
            }
        </script>
    </div>
</body>

</html>