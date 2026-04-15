<?php
function getMonografiId($conn, $wilayah_id, $tahun)
{
    $stmt = $conn->prepare("
        SELECT id FROM monografi_tahun 
        WHERE wilayah_id = ? AND tahun = ?
    ");
    $stmt->bind_param("ii", $wilayah_id, $tahun);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        return $row['id'];
    }

    return null;
}

function createMonografiIfNotExist($conn, $wilayah_id, $tahun, $bulan)
{
    $id = getMonografiId($conn, $wilayah_id, $tahun);

    if (!$id) {
        $stmt = $conn->prepare("
            INSERT INTO monografi_tahun (wilayah_id, tahun, bulan)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("iis", $wilayah_id, $tahun, $bulan);
        $stmt->execute();
        return $stmt->insert_id;
    }

    return $id;
}

function getData($conn, $table, $monografi_id)
{
    if (!$monografi_id)
        return [];

    $stmt = $conn->prepare("SELECT * FROM $table WHERE monografi_id = ?");
    $stmt->bind_param("i", $monografi_id);
    $stmt->execute();
    return $stmt->get_result()->fetch_assoc() ?? [];
}