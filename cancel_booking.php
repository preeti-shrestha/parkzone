<?php
$connection = new mysqli('localhost', 'root', '', 'parkzone');
header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));
$bookId = $connection->real_escape_string($data->book_id);

$sql = "UPDATE book SET ApprovalStatus = 'cancelled' WHERE BookID = $bookId";
if ($connection->query($sql)) {
    echo json_encode(["success" => true]);
} else {
    echo json_encode(["success" => false, "message" => "Failed to cancel"]);
}
?>
