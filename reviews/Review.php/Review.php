<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Credentials: true");
header("Content-Type: application/json");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200); exit();
}

include 'db.php';

$data = json_decode(file_get_contents("php://input"), true);

$user_id = $data['user_id'] ?? '';
$movie_id = $data['movie_id'] ?? '';
$review = trim($data['review'] ?? '');

if (empty($user_id) || empty($movie_id) || empty($review)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Data tidak lengkap"]);
    exit;
}

$stmt = $conn->prepare("INSERT INTO reviews (user_id, movie_id, review) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $user_id, $movie_id, $review);

if ($stmt->execute()) {
    echo json_encode(["success" => true, "message" => "Review berhasil ditambahkan"]);
} else {
    echo json_encode(["success" => false, "message" => "Gagal menambahkan review"]);
}

$stmt->close();
$conn->close();
?>
