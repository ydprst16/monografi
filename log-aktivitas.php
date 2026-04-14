<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT * FROM user_log ORDER BY created_at DESC LIMIT 200");

/* ======================
FILTER
====================== */

$search = $_GET['search'] ?? '';
$user_filter = $_GET['user'] ?? '';
$date = $_GET['date'] ?? '';

$where = [];

if ($search) {
    $where[] = "aktivitas LIKE '%" . $conn->real_escape_string($search) . "%'";
}

if ($user_filter) {
    $where[] = "username='" . $conn->real_escape_string($user_filter) . "'";
}

if ($date) {
    $where[] = "DATE(created_at)='" . $conn->real_escape_string($date) . "'";
}

$where_sql = $where ? "WHERE " . implode(" AND ", $where) : "";

$query = "SELECT * FROM user_log $where_sql ORDER BY created_at DESC LIMIT 300";

$result = $conn->query($query);

/* ======================
STATISTIK
====================== */

$login_success = $conn->query("
SELECT COUNT(*) as total 
FROM user_log 
WHERE aktivitas LIKE '%Login berhasil%'
")->fetch_assoc()['total'];

$login_failed = $conn->query("
SELECT COUNT(*) as total 
FROM user_log 
WHERE aktivitas LIKE '%Login gagal%'
")->fetch_assoc()['total'];

$password_change = $conn->query("
SELECT COUNT(*) as total 
FROM user_log 
WHERE aktivitas LIKE '%password%'
")->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Log Aktivitas</title>

    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

    <div class="wrapper">

        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
                </li>
            </ul>

            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </li>
            </ul>
        </nav>

        <!-- Sidebar -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">

            <a href="dashboard-admin.php" class="brand-link">
                <img src="images/logo.png" class="brand-image img-circle elevation-3">
                <span class="brand-text font-weight-light">Monografi</span>
            </a>

            <div class="sidebar">

                <nav class="mt-2">

                    <ul class="nav nav-pills nav-sidebar flex-column">

                        <li class="nav-item">
                            <a href="dashboard-admin.php" class="nav-link">
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
                            <a href="log-aktivitas.php" class="nav-link active">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Log Aktivitas</p>
                            </a>
                        </li>

                    </ul>

                </nav>
            </div>
        </aside>

        <!-- Content -->

        <div class="content-wrapper">

            <section class="content-header">

                <div class="container-fluid">
                    <h1>Log Aktivitas Sistem</h1>
                </div>

            </section>


            <section class="content">

                <div class="container-fluid">

                    <!-- Statistik -->

                    <div class="row mb-3">

                        <div class="col-md-4">

                            <div class="small-box bg-success">

                                <div class="inner">
                                    <h3><?php echo $login_success; ?></h3>
                                    <p>Login Berhasil</p>
                                </div>

                                <div class="icon">
                                    <i class="fas fa-check"></i>
                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="small-box bg-danger">

                                <div class="inner">
                                    <h3><?php echo $login_failed; ?></h3>
                                    <p>Login Gagal</p>
                                </div>

                                <div class="icon">
                                    <i class="fas fa-times"></i>
                                </div>

                            </div>

                        </div>


                        <div class="col-md-4">

                            <div class="small-box bg-info">

                                <div class="inner">
                                    <h3><?php echo $password_change; ?></h3>
                                    <p>Perubahan Password</p>
                                </div>

                                <div class="icon">
                                    <i class="fas fa-key"></i>
                                </div>

                            </div>

                        </div>

                    </div>


                    <!-- Filter -->

                    <form method="GET" class="mb-3">

                        <div class="row">

                            <div class="col-md-3">

                                <input type="text" name="search" class="form-control" placeholder="Cari aktivitas..."
                                    value="<?php echo htmlspecialchars($search); ?>">

                            </div>

                            <div class="col-md-3">

                                <select name="user" class="form-control">

                                    <option value="">Semua User</option>

                                    <?php
                                    $users = $conn->query("SELECT DISTINCT username FROM user_log");

                                    while ($u = $users->fetch_assoc()):
                                        ?>

                                        <option value="<?php echo $u['username']; ?>" <?php if ($user_filter == $u['username'])
                                               echo 'selected'; ?>>
                                            <?php echo $u['username']; ?>
                                        </option>

                                    <?php endwhile; ?>

                                </select>

                            </div>


                            <div class="col-md-3">

                                <input type="date" name="date" class="form-control"
                                    value="<?php echo htmlspecialchars($date); ?>">

                            </div>

                            <div class="col-md-3">

                                <button class="btn btn-primary">
                                    <i class="fas fa-search"></i> Filter
                                </button>

                                <a href="log-aktivitas.php" class="btn btn-secondary">
                                    Reset
                                </a>

                            </div>

                        </div>

                    </form>


                    <!-- Table -->

                    <div class="card">

                        <div class="card-header">
                            <h3 class="card-title">Riwayat Aktivitas</h3>
                        </div>

                        <div class="card-body table-responsive">

                            <table class="table table-bordered table-striped">

                                <thead>

                                    <tr>

                                        <th>No</th>
                                        <th>Waktu</th>
                                        <th>User</th>
                                        <th>Aktivitas</th>
                                        <th>IP</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <?php
                                    $no = 1;

                                    while ($row = $result->fetch_assoc()):
                                        ?>

                                        <tr>

                                            <td><?php echo $no++; ?></td>

                                            <td><?php echo $row['created_at']; ?></td>

                                            <td><?php echo htmlspecialchars($row['username']); ?></td>

                                            <td><?php echo htmlspecialchars($row['aktivitas']); ?></td>

                                            <td><?php echo $row['ip_address'] ?? '-'; ?></td>

                                        </tr>

                                    <?php endwhile; ?>

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </section>

        </div>


        <footer class="main-footer">

            <strong>
                &copy; 2025 IPDS BPS KOTA DUMAI
            </strong>

        </footer>

    </div>


    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>

</body>

</html>