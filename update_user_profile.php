<?php
header('Content-Type: application/json');
$conn = new mysqli('localhost', 'root', '', 'parkzone');
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Database connection failed"]);
    exit;
}
$data = json_decode(file_get_contents("php://input"));
if (
    isset($data->user_id) &&
    isset($data->name) &&
    isset($data->email) &&
    isset($data->phone) &&
    isset($data->vehicle_no) &&
    isset($data->vehicle_type) &&
    isset($data->username)
) {
    $user_id = $data->user_id;
    $name = trim($data->name);
    $email = trim($data->email);
    $phone = trim($data->phone);
    $vehicle_no = trim($data->vehicle_no);
    $vehicle_type = trim($data->vehicle_type);
    $username = trim($data->username);
    $conflicts = [
        ['field' => 'UserName',   'value' => $username,   'label' => 'Username'],
        ['field' => 'Email',      'value' => $email,      'label' => 'Email'],
        ['field' => 'Phone',      'value' => $phone,      'label' => 'Phone number'],
        ['field' => 'VehicleNo',  'value' => $vehicle_no, 'label' => 'Vehicle number'],
    ];
    foreach ($conflicts as $c) {
        $stmt = $conn->prepare("SELECT UserID FROM user WHERE {$c['field']} = ? AND UserID != ?");
        $stmt->bind_param("si", $c['value'], $user_id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            echo json_encode(["success" => false, "message" => "{$c['label']} already in use"]);
            $stmt->close();
            $conn->close();
            exit;
        }
        $stmt->close();
    }
    $stmt = $conn->prepare("UPDATE user SET Name=?, Email=?, Phone=?, VehicleNo=?, VehicleType=?, UserName=? WHERE UserID=?");
    $stmt->bind_param("ssssssi", $name, $email, $phone, $vehicle_no, $vehicle_type, $username, $user_id);
    if ($stmt->execute()) {
        echo json_encode(["success" => true, "message" => "Profile updated successfully"]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to update profile"]);
    }
    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Missing required fields"]);
}
$conn->close();
?>