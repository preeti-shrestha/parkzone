<?php
$conn = new mysqli("localhost", "root", "", "parkzone");

$locationID = $_GET['location_id'];

$query = "SELECT SlotID, SlotType, SlotStatus FROM slot WHERE LocationID = $locationID ORDER BY SlotType, SlotID ";
$result = $conn->query($query);

$slots = [];
while ($row = $result->fetch_assoc()) {
    $slots[] = $row;
}

echo json_encode($slots);
?>
