<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "movieverse";

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Koneksi gagal: " . $conn->connect_error
    ]);
    exit;
}
?>
