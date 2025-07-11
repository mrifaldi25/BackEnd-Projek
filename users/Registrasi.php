<?php
// ==== CORS SETUP ====
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

// Tangani request preflight OPTIONS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Lanjutkan ke script hanya jika bukan OPTIONS
require_once __DIR__ . '/db.php';

// Baca input JSON dari React
$data = json_decode(file_get_contents("php://input"), true);
$name = trim($data['name'] ?? '');
$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');
$role = trim($data['role'] ?? 'user');

if (!$name || !$email || !$password) {
    echo json_encode(["success" => false, "message" => "Semua field wajib diisi."]);
    exit;
}

$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

$stmt = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)");
$stmt->bind_param("ssss", $name, $email, $hashedPassword, $role);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Registrasi berhasil."]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal registrasi: " . $stmt->error]);
}
?>
