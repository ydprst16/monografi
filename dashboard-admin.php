<?php
session_start();
include 'conn.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$tahun_target = date("Y");

$query = "
SELECT 
    w.id,
    w.kelurahan,
    w.kecamatan,
    mt.tahun
FROM wilayah w
LEFT JOIN monografi_tahun mt ON mt.wilayah_id = w.id
ORDER BY w.kelurahan ASC, mt.tahun DESC
";

$result = $conn->query($query);

$data = [];
$total_kelurahan = 0;
$sudah_update = 0;

while ($row = $result->fetch_assoc()) {
    $id = $row['id'];

    if (!isset($data[$id])) {
        $data[$id] = [
            'kelurahan' => $row['kelurahan'],
            'kecamatan' => $row['kecamatan'],
            'tahun' => []
        ];
        $total_kelurahan++;
    }

    if ($row['tahun']) {
        $data[$id]['tahun'][] = $row['tahun'];
    }
}

foreach ($data as &$row) {
    $tahun_terbaru = $row['tahun'][0] ?? null;

    if ($tahun_terbaru == $tahun_target) {
        $row['status'] = 'sudah';
        $sudah_update++;
    } else {
        $row['status'] = 'belum';
    }
}

$belum_update = $total_kelurahan - $sudah_update;
$persentase = $total_kelurahan > 0 ? round(($sudah_update / $total_kelurahan) * 100) : 0;
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />


    <style>
        .col-custom {
            width: 20%;
        }

        @media(max-width: 1200px) {
            .col-custom {
                width: 25%;
            }
        }

        @media(max-width: 768px) {
            .col-custom {
                width: 50%;
            }
        }

        .card-body {
            font-size: 13.5px;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">

    <div class="wrapper">

        <?php include 'layout/header.php'; ?>
        <?php include 'layout/sidebar.php'; ?>

        <!-- CONTENT -->
        <div class="content-wrapper">
            <section class="content p-3">

                <h4>Dashboard Admin</h4>

                <!-- SUMMARY -->
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h4><?= $sudah_update ?></h4>
                                <p>Update</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h4><?= $belum_update ?></h4>
                                <p>Belum</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h4><?= $persentase ?>%</h4>
                                <p>Progress</p>
                            </div>
                        </div>
                    </div>
                </div>

                <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari...">

                <div class="d-flex flex-wrap" id="kelurahanList">

                    <?php foreach ($data as $row): ?>

                        <div class="col-custom p-1 d-flex">
                            <div class="card shadow-sm w-100">

                                <div class="card-body p-2 d-flex flex-column">

                                    <b><?= htmlspecialchars($row['kelurahan']) ?></b>
                                    <small><?= $row['kecamatan'] ?></small>

                                    <?php if ($row['status'] == 'sudah'): ?>
                                        <div class="text-success mt-1">✔ <?= $tahun_target ?></div>
                                    <?php else: ?>
                                        <div class="text-danger mt-1">⚠ belum ada tahun <?= $tahun_target ?></div>
                                    <?php endif; ?>

                                    <?php if (!empty($row['tahun'])): ?>
                                        <div class="d-flex align-items-center mt-2 mb-2">

                                            <select class="form-control form-control-sm tahun-select mr-1"
                                                data-kelurahan="<?= htmlspecialchars($row['kelurahan']) ?>">
                                                <?php foreach ($row['tahun'] as $i => $th): ?>
                                                    <option value="<?= $th ?>" <?= $i === 0 ? 'selected' : '' ?>>
                                                        <?= $th ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>

                                            <a href="#" class="btn btn-sm btn-info btn-edit mr-1"
                                                data-kelurahan="<?= htmlspecialchars($row['kelurahan']) ?>">
                                                <i class="fas fa-pen"></i>
                                            </a>

                                            <a href="#" class="btn btn-sm btn-success btn-lihat"
                                                data-kelurahan="<?= htmlspecialchars($row['kelurahan']) ?>">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                        </div>
                                    <?php endif; ?>

                                    <a href="input-data.php?kelurahan=<?= urlencode($row['kelurahan']) ?>"
                                        class="btn btn-sm btn-primary mt-auto mb-2">
                                        + Tahun
                                    </a>

                                </div>

                            </div>
                        </div>

                    <?php endforeach; ?>

                </div>

            </section>

        </div>
        <!-- FOOTER  -->
        <?php include 'layout/footer.php'; ?>
    </div>

    <script>
        document.getElementById("searchInput").addEventListener("keyup", function () {
            let filter = this.value.toLowerCase();
            let cards = document.querySelectorAll(".col-custom");

            cards.forEach(card => {
                card.style.display = card.innerText.toLowerCase().includes(filter) ? "" : "none";
            });
        });

        document.querySelectorAll('.card').forEach(card => {

            const select = card.querySelector('.tahun-select');
            const editBtn = card.querySelector('.btn-edit');
            const lihatBtn = card.querySelector('.btn-lihat');

            if (select && editBtn && lihatBtn) {
                function updateLink() {
                    const tahun = select.value;
                    const kelurahan = select.dataset.kelurahan;

                    editBtn.href = `input-data.php?kelurahan=${encodeURIComponent(kelurahan)}&tahun=${tahun}`;
                    lihatBtn.href = `monograph.php?kelurahan=${encodeURIComponent(kelurahan)}&tahun=${tahun}`;
                }

                updateLink();
                select.addEventListener('change', updateLink);
            }

        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.4/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>

</html>