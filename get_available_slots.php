<?php
$conn = new mysqli("localhost", "root", "", "parkzone");

$locationID = $_GET['locationID'];
$start = $_GET['start'];
$end = $_GET['end'];

if (!$locationID || !$start || !$end) {
    http_response_code(400);
    echo json_encode(["error" => "Missing parameters"]);
    exit();
}
$query = "SELECT s.SlotID, s.SlotType, s.SlotStatus
            FROM slot s
            WHERE s.LocationID = $locationID
            AND s.SlotID NOT IN (
                SELECT b.SlotID
                FROM book b
                WHERE b.LocationID = $locationID
                AND ('$start' < b.BookEnd AND '$end' > b.BookStart)
            )";

$result = $conn->query($query);

$slots = [];

while ($row = $result->fetch_assoc()) {
    $slots[] = $row;
}

header('Content-Type: application/json');
echo json_encode($slots);

$conn->close();
?>
