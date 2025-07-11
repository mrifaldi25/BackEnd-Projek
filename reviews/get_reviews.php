<?php
header("Access-Control-Allow-Origin: http://localhost:3000");
header("Content-Type: application/json");

include 'db.php';

$movie_id = $_GET['movie_id'] ?? '';

if (empty($movie_id)) {
    echo json_encode(["success" => false, "message" => "Movie ID diperlukan"]);
    exit;
}

$stmt = $conn->prepare("SELECT * FROM reviews WHERE movie_id = ? ORDER BY created_at DESC");
$stmt->bind_param("s", $movie_id);
$stmt->execute();
$result = $stmt->get_result();

$reviews = [];
while ($row = $result->fetch_assoc()) {
    $reviews[] = $row;
}

echo json_encode(["success" => true, "reviews" => $reviews]);

$stmt->close();
$conn->close();
?>
