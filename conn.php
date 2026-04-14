<?php
$host = getenv('monorail.proxy.rlwy.net');
$user = getenv('root');
$pass = getenv('AwlpHBTfGfDsroRSYEvLpudBuwfdylJB');
$db = getenv('railway');
$port = getenv('32039');

// fallback localhost
if (!$host) {
    $host = "localhost";
    $user = "root";
    $pass = "";
    $db = "monograph_kelurahan";
    $port = 3306;
}

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>