<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$email = trim($data['email'] ?? '');
$password = trim($data['password'] ?? '');

// Validasi
if (empty($email) || empty($password)) {
    echo json_encode(["success" => false, "message" => "Email dan password wajib diisi"]);
    exit;
}

// Ambil data user
$stmt = $conn->prepare("SELECT id, name, email, password, role FROM users WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo json_encode(["success" => false, "message" => "Email tidak ditemukan"]);
    exit;
}

$user = $result->fetch_assoc();

// Verifikasi password hash
if (!password_verify($password, $user['password'])) {
    echo json_encode(["success" => false, "message" => "Password salah"]);
    exit;
}

// Login sukses
echo json_encode([
    "success" => true,
    "message" => "Login berhasil",
    "user" => [
        "id" => $user['id'],
        "name" => $user['name'],
        "email" => $user['email'],
        "role" => $user['role']
    ]
]);
