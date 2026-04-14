<?php
include 'auth.php';
include 'conn.php';

// Pastikan hanya operator yang bisa mengakses halaman ini
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'operator') {
    header("Location: login.php");
    exit();
}

// Ambil kelurahan yang sesuai dengan operator
$kelurahan = $_SESSION['kelurahan'];

// Ambil data yang sesuai dengan kelurahan operator
$query = "SELECT * FROM wilayah WHERE kelurahan = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $kelurahan);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Operator</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <link rel="stylesheet" href="css/dasboard-op.css">
</head>

<body>

    <?php if (isset($_SESSION['notif'])): ?>
        <script>
            Swal.fire({
                icon: '<?= $_SESSION['notif'] ?>',
                title: '<?= $_SESSION['notif'] == "success" ? "Berhasil!" : "Gagal!" ?>'
            });
        </script>
        <?php unset($_SESSION['notif']); ?>
    <?php endif; ?>

    <header>
        Dashboard Operator
        <button class="logout" onclick="openModal()">Logout</button>
    </header>

    <div class="container">
        <h2>Dashboard Operator</h2>
        <h3>Kelurahan: <?php echo htmlspecialchars($kelurahan); ?></h3>

        <h4>Data Wilayah:</h4>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Kelurahan</th>
                    <th>Kecamatan</th>
                    <th>Kota</th>
                    <th>Provinsi</th>
                    <th>Kode Pos</th>
                    <th>Kode Kemendagri</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><?php echo htmlspecialchars($row['kelurahan']); ?></td>
                        <td><?php echo htmlspecialchars($row['kecamatan']); ?></td>
                        <td><?php echo htmlspecialchars($row['kota']); ?></td>
                        <td><?php echo htmlspecialchars($row['provinsi']); ?></td>
                        <td><?php echo htmlspecialchars($row['kode_pos']); ?></td>
                        <td><?php echo htmlspecialchars($row['kode_kemendagri']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <a href="input-data.php?kelurahan=<?php echo urlencode($kelurahan); ?>">Edit Data Monograph</a>
    </div>

    <!-- Modal Logout -->
    <div id="logoutModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Konfirmasi Logout</h2>
                <span onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin keluar?</p>
            </div>
            <div class="modal-footer">
                <button class="cancel" onclick="closeModal()">Batal</button>
                <form action="logout.php" method="POST" style="display: inline;">
                    <button type="submit" class="confirm">Ya, Logout</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById('logoutModal').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('logoutModal').style.display = 'none';
        }

        window.onclick = function (event) {
            const modal = document.getElementById('logoutModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>

</body>

</html>