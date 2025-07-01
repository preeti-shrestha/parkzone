<?php
header('Content-Type: application/json');

$conn = new mysqli('localhost', 'root', '', 'parkzone');

$data = json_decode(file_get_contents("php://input"));

if (isset($data->user_id)) {
    $user_id = $data->user_id;

    $stmt = $conn->prepare("SELECT Name, Email, Phone, VehicleNo, VehicleType, Status FROM user WHERE UserID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        echo json_encode([
            "success" => true,
            "name" => $row["Name"],
            "email" => $row["Email"],
            "phone" => $row["Phone"],
            "vehicle_no" => $row["VehicleNo"],
            "vehicle_type" => $row["VehicleType"],
            "status" => $row["Status"]
        ]);
    } else {
        echo json_encode(["success" => false, "message" => "User not found"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
}

$conn->close();
?>
