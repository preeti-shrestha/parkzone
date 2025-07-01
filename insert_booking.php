<?php
$conn = new mysqli("localhost", "root", "", "parkzone");

$data = json_decode(file_get_contents('php://input'), true);

$UserID = $data['UserID'];
$LocationID = $data['LocationID'];
$SlotID = $data['SlotID'];
$BookStart = $data['BookStart'];
$BookEnd = $data['BookEnd'];
$query = "INSERT INTO book (UserID, LocationID, SlotID, BookStart, BookEnd, ApprovalStatus) 
          VALUES ($UserID, $LocationID, $SlotID, '$BookStart', '$BookEnd', 'pending')";

$response = [];

if ($conn->query($query) === TRUE) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $conn->error;
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
