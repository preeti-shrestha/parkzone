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

$data = json_decode(file_get_contents("php://input"), true);

$username = $conn->real_escape_string($data['username'] ?? '');
$password = $data['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode([
        "success" => false,
        "message" => "Please provide username and password."
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT UserID,Name, PassWord, Status,VehicleType FROM user WHERE UserName = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $row = $result->fetch_assoc();

    if (password_verify($password, $row['PassWord'])) {
        if ($row['Status'] == 'inactive') {
            echo json_encode([
                "success" => false,
                "message" => "User not verified. Please wait till your account is verified."
            ]);
        } else {
            echo json_encode([
                "success" => true,
                "message" => "Login successful",
                "user_id" => $row["UserID"],
                "name" => $row["Name"],
                "vehicle_type"=>$row["VehicleType"]
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Invalid password."
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "User not found."
    ]);
}

$stmt->close();
$conn->close();
?>
