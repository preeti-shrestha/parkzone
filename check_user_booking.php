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

$userID = $_GET['userID'];
$start = $_GET['start'];
$end = $_GET['end'];

$sql = "SELECT * FROM book 
        WHERE UserID = ? 
        AND BookStatus != 'ended' 
        AND (
            (BookStart < ? AND BookEnd > ?) OR 
            (BookStart >= ? AND BookStart < ?)
        )";

$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $userID, $end, $start, $start, $end);
$stmt->execute();
$result = $stmt->get_result();

$conflict = $result->num_rows > 0;
echo json_encode(["overlap" => $conflict]);
?>
