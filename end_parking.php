<?php
header('Content-Type: application/json');
require_once 'connection.php';

$response = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents("php://input"));

    if (isset($data->book_id)) {
        $bookID = intval($data->book_id);
        date_default_timezone_set('Asia/Kathmandu');
        $currentDateTime = date("Y-m-d H:i:s"); // current timestamp

        $sql = "UPDATE book SET BookEnd = ? WHERE BookID = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("si", $currentDateTime, $bookID);

        if ($stmt->execute()) {
            $response['success'] = true;
            $response['message'] = "Parking ended successfully";
        } else {
            $response['success'] = false;
            $response['message'] = "Database update failed";
        }

        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = "Invalid input";
    }
} else {
    $response['success'] = false;
    $response['message'] = "Invalid request method";
}

echo json_encode($response);
?>
