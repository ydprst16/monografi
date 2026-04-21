<?php
session_start();

$_POST = array_map(function ($v) {
    return ($v === '') ? null : $v;
}, $_POST);

include 'conn.php';
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
include 'helper.php';

/* =========================
   ROLE & REDIRECT
========================= */
$role = $_SESSION['role'] ?? '';
$redirect = ($role == 'admin') ? 'dashboard-admin.php' :
    (($role == 'operator') ? 'dashboard-operator.php' : 'index.php');

/* =========================
   VALIDASI
========================= */
$wilayah_id = $_POST['wilayah_id'] ?? null;
$kelurahan = $_POST['kelurahan'] ?? '';

if (!$wilayah_id) {
    header("Location: input-data.php?status=error");
    exit;
}

/* =========================
   MONOGRAFI (KEY UTAMA)
========================= */
$tahun = $_POST['tahun'] ?? date('Y');
$bulan = $_POST['bulan'] ?? '';

$monografi_id = createMonografiIfNotExist($conn, $wilayah_id, $tahun, $bulan);

/* =========================
   FUNCTION UPSERT
========================= */
function saveOrUpdate($conn, $table, $data, $monografi_id)
{
    $check = $conn->prepare("SELECT id FROM $table WHERE monografi_id = ?");
    $check->bind_param("i", $monografi_id);
    $check->execute();
    $res = $check->get_result();

    if ($row = $res->fetch_assoc()) {

        // UPDATE
        $fields = [];
        foreach ($data as $key => $val) {
            $fields[] = "$key = ?";
        }

        $sql = "UPDATE $table SET " . implode(",", $fields) . " WHERE monografi_id = ?";
        $stmt = $conn->prepare($sql);

        $types = str_repeat("s", count($data)) . "i";
        $params = array_values($data);
        $params[] = $monografi_id;

        $stmt->bind_param($types, ...$params);
        $stmt->execute();

    } else {

        // INSERT
        $data['monografi_id'] = $monografi_id;

        $fields = implode(",", array_keys($data));
        $placeholders = implode(",", array_fill(0, count($data), "?"));

        $sql = "INSERT INTO $table ($fields) VALUES ($placeholders)";
        $stmt = $conn->prepare($sql);

        $types = str_repeat("s", count($data));
        $stmt->bind_param($types, ...array_values($data));
        $stmt->execute();
    }
}

/* =========================
   TRY SIMPAN
========================= */
try {

    /* =========================
       1. BATAS
    ========================= */
    saveOrUpdate($conn, 'wilayah_batas_jarak', [
        'tipologi_kelurahan' => $_POST['tipologi_kelurahan'] ?? null,
        'luas_wilayah' => $_POST['luas_wilayah'] ?? null,
        'batas_wilayah_utara' => $_POST['batas_wilayah_utara'] ?? null,
        'batas_wilayah_selatan' => $_POST['batas_wilayah_selatan'] ?? null,
        'batas_wilayah_barat' => $_POST['batas_wilayah_barat'] ?? null,
        'batas_wilayah_timur' => $_POST['batas_wilayah_timur'] ?? null,
        'jarak_pusat_pemerintahan_kecamatan' => $_POST['jarak_pusat_pemerintahan_kecamatan'] ?? null,
        'jarak_pusat_pemerintahan_kota' => $_POST['jarak_pusat_pemerintahan_kota'] ?? null,
        'jarak_ibukota_kabupaten' => $_POST['jarak_ibukota_kabupaten'] ?? null,
        'jarak_ibukota_provinsi' => $_POST['jarak_ibukota_provinsi'] ?? null,
    ], $monografi_id);

    /* =========================
       2. DEMOGRAFI
    ========================= */
    saveOrUpdate($conn, 'demografi', [
        'jumlah_penduduk_laki_laki' => $_POST['jumlah_penduduk_laki_laki'] ?? null,
        'jumlah_penduduk_perempuan' => $_POST['jumlah_penduduk_perempuan'] ?? null,
        'jumlah_penduduk_usia_0_15' => $_POST['jumlah_penduduk_usia_0_15'] ?? null,
        'jumlah_penduduk_usia_15_65' => $_POST['jumlah_penduduk_usia_15_65'] ?? null,
        'jumlah_penduduk_usia_65_keatas' => $_POST['jumlah_penduduk_usia_65_keatas'] ?? null,
        'mayoritas_pekerjaan' => $_POST['mayoritas_pekerjaan'] ?? null,
        'jumlah_penduduk_miskin_kk' => $_POST['jumlah_penduduk_miskin_kk'] ?? null,
        'jumlah_penduduk_miskin_jiwa' => $_POST['jumlah_penduduk_miskin_jiwa'] ?? null,
        'umr_kabupaten_kota' => $_POST['umr_kabupaten_kota'] ?? null,
    ], $monografi_id);

    /* =========================
       3. SARANA
    ========================= */
    saveOrUpdate($conn, 'sarana', [
        'kantor_kelurahan' => $_POST['kantor_kelurahan'] ?? null,
        'puskesmas' => $_POST['puskesmas'] ?? null,
        'ukbm_posyandu' => $_POST['ukbm_posyandu'] ?? null,
        'poliklinik' => $_POST['poliklinik'] ?? null,
        'masjid' => $_POST['masjid'] ?? 0,
        'mushola' => $_POST['mushola'] ?? 0,
        'gereja' => $_POST['gereja'] ?? 0,
        'pura' => $_POST['pura'] ?? 0,
        'vihara' => $_POST['vihara'] ?? 0,
        'klenteng' => $_POST['klenteng'] ?? 0,
        'olahraga' => $_POST['olahraga'] ?? 0,
        'kesenian_budaya' => $_POST['kesenian_budaya'] ?? 0,
        'balai_pertemuan' => $_POST['balai_pertemuan'] ?? 0,
        'sarana_lainnya' => $_POST['sarana_lainnya'] ?? null,
    ], $monografi_id);

    /* =========================
   4. SARANA DETAIL (FIX FINAL)
========================= */

    // ambil sarana_id berdasarkan monografi
    $stmt = $conn->prepare("SELECT id FROM sarana WHERE monografi_id = ?");
    $stmt->bind_param("i", $monografi_id);
    $stmt->execute();
    $sarana = $stmt->get_result()->fetch_assoc();

    $sarana_id = $sarana['id'] ?? null;

    if ($sarana_id) {

        // HAPUS DATA LAMA (pakai sarana_id, bukan monografi_id)
        $stmt = $conn->prepare("DELETE FROM sarana_detail WHERE sarana_id = ?");
        $stmt->bind_param("i", $sarana_id);
        $stmt->execute();

        $jenis_list = [
            'masjid',
            'mushola',
            'gereja',
            'pura',
            'vihara',
            'klenteng',
            'olahraga',
            'kesenian_budaya',
            'balai_pertemuan'
        ];

        foreach ($jenis_list as $jenis) {
            $arr = $_POST[$jenis . '_nama'] ?? [];

            foreach ($arr as $nama) {
                if (!empty($nama)) {
                    $stmt = $conn->prepare("
                    INSERT INTO sarana_detail (sarana_id, jenis, nama)
                    VALUES (?, ?, ?)
                ");
                    $stmt->bind_param("iss", $sarana_id, $jenis, $nama);
                    $stmt->execute();
                }
            }
        }
    }

    /* =========================
       5. PROGRAM BANTUAN
    ========================= */
    saveOrUpdate($conn, 'program_bantuan', [
        'skpd_sudah' => $_POST['skpd_sudah'] ?? null,
        'program_pusat' => $_POST['program_pusat'] ?? null,
        'program_provinsi' => $_POST['program_provinsi'] ?? null,
        'program_kabupaten' => $_POST['program_kabupaten'] ?? null,
        'apbd' => $_POST['apbd'] ?? null,
        'bantuan_pusat' => $_POST['bantuan_pusat'] ?? null,
        'bantuan_provinsi' => $_POST['bantuan_provinsi'] ?? null,
        'bantuan_kab_kota' => $_POST['bantuan_kab_kota'] ?? null,
        'bantuan_luar_negeri' => $_POST['bantuan_luar_negeri'] ?? null,
        'bantuan_gotong_royong' => $_POST['bantuan_gotong_royong'] ?? null,
        'bantuan_sumber_lain' => $_POST['bantuan_sumber_lain'] ?? null,
        'bulan' => $_POST['bulan'] ?? null,
        'tahun' => $_POST['tahun'] ?? null,
    ], $monografi_id);

    /* =========================
       6. PENDIDIKAN
    ========================= */
    saveOrUpdate($conn, 'pendidikan', [
        'lulusan_tk' => $_POST['lulusan_tk'] ?? null,
        'lulusan_sd' => $_POST['lulusan_sd'] ?? null,
        'lulusan_smp' => $_POST['lulusan_smp'] ?? null,
        'lulusan_sma' => $_POST['lulusan_sma'] ?? null,
        'lulusan_akademi' => $_POST['lulusan_akademi'] ?? null,
        'lulusan_sarjana' => $_POST['lulusan_sarjana'] ?? null,
        'lulusan_pascasarjana' => $_POST['lulusan_pasca_sarjana'] ?? null,
        'lulusan_pondok_pesantren' => $_POST['lulusan_pondok_pesantren'] ?? null,
        'lulusan_pendidikan_keagamaan' => $_POST['lulusan_pendidikan_keagamaan'] ?? null,
        'lulusan_slb' => $_POST['lulusan_slb'] ?? null,
        'lulusan_kursus_keterampilan' => $_POST['lulusan_kursus_keterampilan'] ?? null,
        'prasarana_paud' => $_POST['prasarana_paud'] ?? null,
        'prasarana_tk' => $_POST['prasarana_tk'] ?? null,
        'prasarana_sd' => $_POST['prasarana_sd'] ?? null,
        'prasarana_smp' => $_POST['prasarana_smp'] ?? null,
        'prasarana_sma' => $_POST['prasarana_sma'] ?? null,
        'prasarana_pt' => $_POST['prasarana_pt'] ?? null,
    ], $monografi_id);

    /* =========================
       7. APARATUR
    ========================= */
    saveOrUpdate($conn, 'aparatur_lembaga', [
        'nama_lurah' => $_POST['nama_lurah'] ?? null,
        'nama_sekretaris' => $_POST['nama_sekretaris'] ?? null,
        'golongan_i' => $_POST['golongan_i'] ?? null,
        'golongan_ii' => $_POST['golongan_ii'] ?? null,
        'golongan_iii' => $_POST['golongan_iii'] ?? null,
        'golongan_iv' => $_POST['golongan_iv'] ?? null,
        'lpm_pengurus' => $_POST['lpm_pengurus'] ?? null,
        'lpm_kegiatan' => $_POST['lpm_kegiatan'] ?? null,
        'lpm_buku_administrasi' => $_POST['lpm_buku_administrasi'] ?? null,
        'lpm_dana' => $_POST['lpm_dana'] ?? null,
        'tp_pkk_pengurus' => $_POST['tp_pkk_pengurus'] ?? null,
        'tp_pkk_kegiatan' => $_POST['tp_pkk_kegiatan'] ?? null,
        'tp_pkk_buku' => $_POST['tp_pkk_buku'] ?? null,
        'tp_pkk_dana' => $_POST['tp_pkk_dana'] ?? null,
        'rt' => $_POST['rt'] ?? null,
        'penghasilan_rt' => $_POST['penghasilan_rt'] ?? null,
        'karang_taruna_jumlah' => $_POST['karang_taruna_jumlah'] ?? null,
        'karang_taruna_pengurus' => $_POST['karang_taruna_pengurus'] ?? null,
        'lembaga_adat' => $_POST['lembaga_adat'] ?? null,
        'lembaga_lainnya' => $_POST['lembaga_lainnya'] ?? null,
    ], $monografi_id);

    $_SESSION['notif'] = 'success';
    header("Location: $redirect");
    exit;

} catch (Exception $e) {

    die("ERROR SIMPAN: " . $e->getMessage());
}