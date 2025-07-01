<?php
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");

$conn = new mysqli("localhost", "root", "", "parkzone");

if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed."
    ]);
    exit;
}

$user_id = isset($_GET['user_id']) ? intval($_GET['user_id']) : 0;
if ($user_id <= 0) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid user ID."
    ]);
    exit;
}

$sql = "SELECT COUNT(*) as booking_count FROM book WHERE UserID = ? AND BookStatus IN ('ongoing', 'upcoming')";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $hasBooking = $row['booking_count'] > 0;
    echo json_encode([
        "success" => true,
        "has_booking" => $hasBooking
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Failed to check booking status."
    ]);
}

$stmt->close();
$conn->close();
?>