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

// Tambah
if (isset($_POST['tambah'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $role = $_POST['role'];
    $kelurahan = $_POST['kelurahan'];

    mysqli_query($conn, "INSERT INTO users (username, password, role, kelurahan) 
    VALUES ('$username','$password','$role','$kelurahan')");
}

// Hapus
if (isset($_GET['hapus'])) {
    $id = intval($_GET['hapus']);
    mysqli_query($conn, "DELETE FROM users WHERE id=$id");
}

// Edit
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result_edit = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");
    $edit_data = mysqli_fetch_assoc($result_edit);
}

// Update
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $username = $_POST['username'];
    $role = $_POST['role'];
    $kelurahan = $_POST['kelurahan'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        mysqli_query($conn, "UPDATE users SET username='$username', password='$password', role='$role', kelurahan='$kelurahan' WHERE id=$id");
    } else {
        mysqli_query($conn, "UPDATE users SET username='$username', role='$role', kelurahan='$kelurahan' WHERE id=$id");
    }
}

// Dropdown kelurahan
$kelurahan_list = [];
$res = mysqli_query($conn, "SELECT * FROM wilayah ORDER BY kelurahan ASC");
while ($r = mysqli_fetch_assoc($res))
    $kelurahan_list[] = $r;

// Data operator
$result = mysqli_query($conn, "SELECT * FROM users WHERE role='operator'");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Kelola Operator</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.bootstrap4.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

    <style>
        .dt-buttons .btn {
            margin-left: 5px;
        }

        #operatorTable td:first-child {
            text-align: center;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
    <div class="wrapper">

                <?php include 'layout/header.php'; ?>
                <?php include 'layout/sidebar.php'; ?>

        <div class="content-wrapper">
            <section class="content p-3">

                <div class="container-fluid">

                    <!-- FORM -->
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><?= $edit_data ? 'Edit Operator' : 'Tambah Operator' ?></h3>
                        </div>

                        <form method="POST">
                            <div class="card-body">

                                                                <?php if ($edit_data): ?>
                                    <input type="hidden" name="id" value="<?= $edit_data['id'] ?>">
                                                                <?php endif; ?>

                                <input type="text" name="username" class="form-control mb-2" placeholder="Username"
                                    value="<?= $edit_data['username'] ?? '' ?>" required>

                                <input type="password" name="password" class="form-control mb-2" placeholder="Password">

                                <select name="kelurahan" class="form-control mb-2" required>
                                    <option value="">-- Pilih Kelurahan --</option>
                                                                        <?php foreach ($kelurahan_list as $k): ?>
                                        <option value="<?= $k['kelurahan'] ?>" <?= (isset($edit_data['kelurahan']) && $edit_data['kelurahan'] == $k['kelurahan']) ? 'selected' : '' ?>>
                                                                                    <?= $k['kelurahan'] ?>
                                        </option>
                                                                        <?php endforeach; ?>
                                </select>

                                <input type="hidden" name="role" value="operator">

                            </div>

                            <div class="card-footer">
                                <button type="submit" name="<?= $edit_data ? 'update' : 'tambah' ?>"
                                    class="btn btn-primary">
                                                                        <?= $edit_data ? 'Update' : 'Tambah' ?>
                                </button>
                            </div>

                        </form>
                    </div>

                    <!-- TABLE -->
                    <div class="card">
                        <div class="card-body">

                            <table id="operatorTable" class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th>Username</th>
                                        <th>Kelurahan</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>

                                <tbody>
                                                                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                        <tr>
                                            <td></td>
                                            <td><?= htmlspecialchars($row['username']) ?></td>
                                            <td><?= htmlspecialchars($row['kelurahan']) ?></td>
                                            <td>
                                                <a href="?edit=<?= $row['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                                <a href="?hapus=<?= $row['id'] ?>" onclick="return confirm('Hapus?')"
                                                    class="btn btn-danger btn-sm">Hapus</a>
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

                <?php include 'layout/footer.php'; ?>

    </div>

    <!-- JS -->
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

                pageLength: 10,
                lengthMenu: [[10, 25, 50], [10, 25, 50]],

                columnDefs: [{ targets: 0, orderable: false, searchable: false }],
                order: [[1, 'asc']],

                dom:
                    "<'row mb-1'<'col-md-12 d-flex justify-content-end'B>>" +
                    "<'row mb-2'<'col-md-6'l><'col-md-6 text-right'f>>" +
                    "<'row'<'col-12'tr>>" +
                    "<'row mt-2'<'col-md-5'i><'col-md-7'p>>",

                buttons: [
                    { extend: 'csvHtml5', text: 'CSV', className: 'btn btn-success btn-sm' },
                    { extend: 'excelHtml5', text: 'Excel', className: 'btn btn-success btn-sm' },
                    { extend: 'print', text: 'Print', className: 'btn btn-info btn-sm' }
                ],

                drawCallback: function (settings) {
                    var api = this.api();
                    var start = api.page.info().start;

                    api.column(0).nodes().each(function (cell, i) {
                        cell.innerHTML = start + i + 1;
                    });
                }

            });
        });
    </script>

</body>

</html>