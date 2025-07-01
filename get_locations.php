<?php
$conn = new mysqli("localhost", "root", "", "parkzone");

$query = "SELECT LocationID, LocationName, LocationLat, LocationLong FROM parklocation";
$result = $conn->query($query);

$locations = [];
while ($row = $result->fetch_assoc()) {
    $locations[] = $row;
}

echo json_encode($locations);
?>
