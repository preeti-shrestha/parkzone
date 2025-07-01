<?php
$connection = new mysqli('localhost', 'root', '', 'parkzone');
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->user_id)) {
    echo json_encode(["success" => false, "message" => "User ID required"]);
    exit;
}

$user_id = $connection->real_escape_string($data->user_id);

$sql = "SELECT b.BookID, b.BookStatus, b.BookStart, b.BookEnd, b.ApprovalStatus,b.LocationID, l.LocationName, b.SlotID, s.SlotType
        FROM book b
        LEFT JOIN parklocation l ON b.LocationID = l.LocationID
        LEFT JOIN slot s ON b.SlotID = s.SlotID
        WHERE b.UserID = $user_id AND b.BookStatus IN ('ongoing', 'upcoming')
        order by BookStatus;
    ";

$result = $connection->query($sql);
$bookings = [];

while ($row = $result->fetch_assoc()) {
    $bookings[] = $row;
}

echo json_encode([
    "success" => true,
    "bookings" => $bookings
    
]);
?>