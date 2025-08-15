<?php
header('Content-Type: application/json');

$conn = new mysqli("localhost", "root", "", "parkzone");
$data = json_decode(file_get_contents("php://input"));

if (isset($data->user_id)) {
    $userId = $conn->real_escape_string($data->user_id);

    $query = "
        SELECT 
            b.BookID as book_id,
            b.BookStart as book_start,
            b.BookEnd as book_end,
            b.ApprovalStatus as approval_status,
            l.LocationID as location_id,
            l.LocationName as location_name,
            s.SlotID as slot_id,
            s.SlotType as slot_type
        FROM book b
        JOIN slot s ON b.SlotID = s.SlotID
        JOIN parklocation l ON b.LocationID = l.LocationID
        WHERE b.BookStatus = 'upcoming' AND b.UserID = '$userId'
        ORDER BY b.BookStart DESC
    ";

    $result = $conn->query($query);

    $upcoming = [];
    while ($row = $result->fetch_assoc()) {
        $upcoming[] = $row;
    }

    echo json_encode(["success" => true, "data" => $upcoming]);
} else {
    echo json_encode(["success" => false, "message" => "User ID not provided."]);
}
?>