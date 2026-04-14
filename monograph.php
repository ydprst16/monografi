<?php
include 'auth.php';
include 'conn.php';

$kelurahan = $_GET['kelurahan'] ?? '';
if (!$kelurahan)
    die("Kelurahan tidak dipilih");

// ================= FUNCTION =================
function getData($conn, $table, $id)
{
    $stmt = $conn->prepare("SELECT * FROM $table WHERE wilayah_id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?? [];
}

// ================= DATA =================
$stmt = $conn->prepare("SELECT * FROM wilayah WHERE kelurahan = ?");
$stmt->bind_param("s", $kelurahan);
$stmt->execute();
$wilayah = $stmt->get_result()->fetch_assoc();

if (!$wilayah)
    die("Data tidak ditemukan");

$batas_jarak = getData($conn, 'wilayah_batas_jarak', $wilayah['id']);
$demografi = getData($conn, 'demografi', $wilayah['id']);
$sarana = getData($conn, 'sarana', $wilayah['id']);
$pendidikan = getData($conn, 'pendidikan', $wilayah['id']);
$program_bantuan = getData($conn, 'program_bantuan', $wilayah['id']);
$aparatur_lembaga = getData($conn, 'aparatur_lembaga', $wilayah['id']);

function tf($label, $value)
{
    $value = ($value === null || $value === '') ? '-' : $value;
    ?>
    <div class="flex justify-between border-b border-gray-90 py-[3px] gap-2 hover:bg-gray-50 px-1 rounded">
        <span class="text-gray-700"><?= $label ?></span>
        <span class="text-gray-900"><?= htmlspecialchars($value) ?></span>
    </div>
<?php }

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Monografi</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            overflow: visible !important;
        }

        h3 {
            border-bottom: 1px solid #d1d5db;
            padding-bottom: 2px;
            margin-top: 10px;
        }

        @media print {
            @page {
                size: A3 landscape;
                margin: 0.5cm;
            }

            /* matikan semua efek modern */
            * {
                background: white !important;
                box-shadow: none !important;
                backdrop-filter: none !important;
            }

            /* overflow wajib dimatikan */
            .overflow-y-auto {
                overflow: visible !important;
            }

            /* hilangkan tombol */
            button {
                display: none !important;
            }

        }
    </style>
</head>

<!-- <body class="bg-gray-100 text-[11px] leading-tight"> -->

<body class="text-[11.5px] leading-tight bg-[linear-gradient(135deg,#f1f5f9,#e0f2fe,#f8fafc)]">

    <!-- HEADER -->
    <div
        class="flex justify-between items-center px-6 py-4 bg-white/80 backdrop-blur-md shadow-sm border-b border-gray-200">

        <div class="flex items-center gap-3">
            <img src="images/logo.png" class="w-12 h-14">

            <div>
                <div class="font-bold text-base tracking-wide">
                    LAPORAN MONOGRAFI
                </div>
                <div class="text-sm text-gray-600">
                    Kelurahan <?= strtoupper($wilayah['kelurahan']) ?>
                </div>
                <div class="text-[11px] text-gray-400">
                    <?= $program_bantuan['bulan'] ?? '-' ?> <?= $program_bantuan['tahun'] ?? '-' ?>
                </div>
            </div>
        </div>

        <div class="flex gap-2">
            <button onclick="window.print()"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-medium shadow hover:bg-blue-700 transition">
                Cetak
            </button>

            <button
                class="px-4 py-2 bg-green-600 text-white rounded-lg text-sm font-medium shadow hover:bg-green-700 transition">
                Word
            </button>
        </div>

    </div>

    <!-- GRID -->
    <div class="grid grid-cols-4 gap-3 px-3 pt-3">

        <!-- ================= KOLOM 1 ================= -->
        <div
            class="bg-white/90 backdrop-blur-sm p-3 rounded-xl shadow-md border border-gray-200 h-full flex flex-col hover:shadow-lg transition">
            <div class="overflow-y-auto pr-2 space-y-2">

                <h3 class="font-bold uppercase mb-1">Identitas</h3>
                <?php
                tf('Kelurahan', $wilayah['kelurahan']);
                tf('Tahun Pembentukan', $wilayah['tahun_pembentukan']);
                tf('Kecamatan', $wilayah['kecamatan']);
                tf('Kota', $wilayah['kota']);
                tf('Provinsi', $wilayah['provinsi']);
                tf('Kode Pos', $wilayah['kode_pos']);
                tf('Kode Kemendagri', $wilayah['kode_kemendagri']);
                ?>

                <h3 class="mt-2 mb-1 font-bold uppercase">Data Umum</h3>
                <?php
                tf('Tipologi', $batas_jarak['tipologi_kelurahan']);
                tf('Luas', ($batas_jarak['luas_wilayah'] ?? '-') . ' km²');
                ?>

                <h3 class="mt-2 mb-1 font-bold uppercase">Batas</h3>
                <?php
                tf('Utara', $batas_jarak['batas_wilayah_utara']);
                tf('Selatan', $batas_jarak['batas_wilayah_selatan']);
                tf('Barat', $batas_jarak['batas_wilayah_barat']);
                tf('Timur', $batas_jarak['batas_wilayah_timur']);
                ?>

                <h3 class="mt-2 mb-1 font-bold uppercase">Jarak dari Pusat Pemerintahan</h3>
                <?php
                tf('Kecamatan', ($batas_jarak['jarak_pusat_pemerintahan_kecamatan'] ?? '-') . ' km');
                tf('Kota', ($batas_jarak['jarak_pusat_pemerintahan_kota'] ?? '-') . ' km');
                tf('Provinsi', ($batas_jarak['jarak_ibukota_provinsi'] ?? '-') . ' km');
                ?>

                <h3 class="font-bold uppercase mb-1">Jumlah Penduduk</h3>
                <?php
                tf('Laki-laki', $demografi['jumlah_penduduk_laki_laki'] . ' org');
                tf('Perempuan', $demografi['jumlah_penduduk_perempuan'] . ' org');
                tf('0-15', $demografi['jumlah_penduduk_usia_0_15'] . ' org');
                tf('15-65', $demografi['jumlah_penduduk_usia_15_65'] . ' org');
                tf('>65', $demografi['jumlah_penduduk_usia_65_keatas'] . ' org');
                //echo '<br>';
                tf('Mayoritas Pekerjaan', $demografi['mayoritas_pekerjaan']);
                ?>
            </div>
        </div>

        <!-- ================= KOLOM 2 ================= -->
        <div
            class="bg-white/90 backdrop-blur-sm p-3 rounded-xl shadow-md border border-gray-200 h-full flex flex-col hover:shadow-lg transition">
            <div class="overflow-y-auto pr-2 space-y-2">
                <h3 class="mb-1 font-bold uppercase">Kemiskinan</h3>
                <?php
                tf(
                    'Jumlah Penduduk Miskin',
                    $demografi['jumlah_penduduk_miskin_kk'] . ' KK / ' .
                    $demografi['jumlah_penduduk_miskin_jiwa'] . ' Jiwa'
                );
                tf('UMR (Rp.)', $demografi['umr_kabupaten_kota']);
                ?>

                <h3 class="mt-2 font-bold uppercase mb-1">Sarana Prasarana</h3>
                <?php
                tf('Kantor Kelurahan', $sarana['kantor_kelurahan']);
                ?>

                <h3 class="mt-2 mb-1 font-bold uppercase">Prasarana Kesehatan</h3>
                <?php
                tf('Puskesmas', $sarana['puskesmas']);
                tf('Posyandu', $sarana['ukbm_posyandu']);
                tf('Poliklinik', $sarana['poliklinik']);
                ?>

                <h3 class="mt-2 mb-1 font-bold uppercase">Prasarana Ibadah</h3>
                <?php
                tf('Masjid', $sarana['masjid']);
                tf('Mushola', $sarana['mushola']);
                tf('Gereja', $sarana['gereja']);
                tf('Pura', $sarana['pura']);
                tf('Vihara', $sarana['vihara']);
                tf('Klenteng', $sarana['klenteng']);
                ?>

                <h3 class="font-bold uppercase mb-1">Prasarana Pendidikan</h3>
                <?php
                tf('PAUD', $pendidikan['prasarana_paud']);
                tf('TK', $pendidikan['prasarana_tk']);
                tf('SD', $pendidikan['prasarana_sd']);
                tf('SMP', $pendidikan['prasarana_smp']);
                tf('SMA', $pendidikan['prasarana_sma']);
                tf('Perguruan Tinggi', $pendidikan['prasarana_pt']);
                ?>

                <h3 class="font-bold uppercase mb-1">Prasarana Umum</h3>
                <?php
                tf('Olahraga', $sarana['olahraga']);
                tf('Balai Pertemuan', $sarana['balai_pertemuan']);
                tf('Kesenian/Budaya', $sarana['kesenian_budaya']);
                //tf('Lainnya', $sarana['sarana_lainnya']);
                ?>
            </div>
        </div>

        <!-- ================= KOLOM 3 ================= -->
        <div
            class="bg-white/90 backdrop-blur-sm p-3 rounded-xl shadow-md border border-gray-200 h-full flex flex-col hover:shadow-lg transition">
            <div class="overflow-y-auto pr-2 space-y-2">
                <h3 class="font-bold uppercase mb-1">Lulusan Pendidikan Umum</h3>
                <?php
                tf('Taman Kanak-kanak', ($pendidikan['lulusan_tk'] ?? '') . ' orang');
                tf('Sekolah Dasar', ($pendidikan['lulusan_sd'] ?? '') . ' orang');
                tf('SMP', ($pendidikan['lulusan_smp'] ?? '') . ' orang');
                tf('SMA/SMU', ($pendidikan['lulusan_sma'] ?? '') . ' orang');
                tf('Akademi/D1-D3', ($pendidikan['lulusan_akademi'] ?? '') . ' orang');
                tf('Sarjana', ($pendidikan['lulusan_sarjana'] ?? '') . ' orang');
                tf('Pasca Sarjana', ($pendidikan['lulusan_pascasarjana'] ?? '') . ' orang');
                ?>

                <h3 class="mt-2 mb-1 font-bold uppercase">Lulusan Pendidikan Khusus</h3>
                <?php
                tf('Pondok Pesantren', ($pendidikan['lulusan_pondok_pesantren'] ?? '') . ' orang');
                tf('Lulusan Pendidikan Keagamaan', ($pendidikan['lulusan_pendidikan_keagamaan'] ?? '') . ' orang');
                tf('Lulusan SLB', ($pendidikan['lulusan_slb'] ?? '') . ' orang');
                tf('Lulusan Kursus Keterampilan', ($pendidikan['lulusan_kursus_keterampilan'] ?? '') . ' orang');
                ?>

                <h3 class="mt-2 mb-1 font-bold uppercase">Aparatur</h3>
                <?php
                tf('Lurah', $aparatur_lembaga['nama_lurah']);
                tf('Sekretaris', $aparatur_lembaga['nama_sekretaris']);
                tf('Gol I', $aparatur_lembaga['golongan_i'] . ' org');
                tf('Gol II', $aparatur_lembaga['golongan_ii'] . ' org');
                tf('Gol III', $aparatur_lembaga['golongan_iii'] . ' org');
                tf('Gol IV', $aparatur_lembaga['golongan_iv'] . ' org');
                ?>

                <h3 class="mt-2 mb-1 font-bold uppercase">Program</h3>
                <?php
                tf('Pusat', $program_bantuan['program_pusat']);
                tf('Provinsi', $program_bantuan['program_provinsi']);
                tf('Kab/Kota', $program_bantuan['program_kabupaten']);
                ?>
            </div>
        </div>
        <!-- ================= KOLOM 4 ================= -->
        <div
            class="bg-white/90 backdrop-blur-sm p-3 rounded-xl shadow-md border border-gray-200 h-full flex flex-col hover:shadow-lg transition">
            <div class="overflow-y-auto pr-2 space-y-2">
                <h3 class="font-bold uppercase mb-1">Keuangan</h3>
                <?php
                $skpd = isset($program_bantuan['skpd_sudah']) ? ($program_bantuan['skpd_sudah'] ? 'Sudah' : 'Belum') : '-';

                tf('APBD', number_format($program_bantuan['apbd'] ?? 0, 0, ',', '.'));
                tf('SKPD', $skpd);
                tf('Pusat', number_format($program_bantuan['bantuan_pusat'] ?? 0, 0, ',', '.'));
                tf('Provinsi', number_format($program_bantuan['bantuan_provinsi'] ?? 0, 0, ',', '.'));
                tf('Kab/Kota', number_format($program_bantuan['bantuan_kab_kota'] ?? 0, 0, ',', '.'));
                tf('Luar Negeri', number_format($program_bantuan['bantuan_luar_negeri'] ?? 0, 0, ',', '.'));
                tf('Gotong Royong', number_format($program_bantuan['bantuan_gotong_royong'] ?? 0, 0, ',', '.'));
                //tf('Lainnya', $program_bantuan['bantuan_sumber_lain']);
                ?>

                <h3 class="mt-2 mb-1 font-bold uppercase">Kelembagaan</h3>
                <?php
                tf('LPM Pengurus', $aparatur_lembaga['lpm_pengurus']);
                tf('LPM Kegiatan', $aparatur_lembaga['lpm_kegiatan']);
                tf('LPM Buku', $aparatur_lembaga['lpm_buku_administrasi']);
                tf('LPM Dana', $aparatur_lembaga['lpm_dana']);
                tf('PKK Pengurus', $aparatur_lembaga['tp_pkk_pengurus']);
                tf('PKK Kegiatan', $aparatur_lembaga['tp_pkk_kegiatan']);
                tf('PKK Buku', $aparatur_lembaga['tp_pkk_buku']);
                tf('PKK Dana', $aparatur_lembaga['tp_pkk_dana']);
                tf('RT', $aparatur_lembaga['rt'] . ' RT');
                tf('Penghasilan RT', $aparatur_lembaga['penghasilan_rt']);
                tf('Karang Taruna', $aparatur_lembaga['karang_taruna_jumlah']);
                tf('Pengurus', $aparatur_lembaga['karang_taruna_pengurus']);
                tf('Adat', $aparatur_lembaga['lembaga_adat']);
                tf('Lainnya', $aparatur_lembaga['lembaga_lainnya']);
                ?>
            </div>
        </div>
</body>

</html>