<?php
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");
$host = "localhost";
$user = "root";
$password = "";
$dbname = "parkzone";
$conn = new mysqli($host, $user, $password, $dbname);
if ($conn->connect_error) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection failed"
    ]);
    exit;
}
$fullname = $_POST['fullname'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$vehicleno = $_POST['vehicleno'] ?? '';
$vehicletype = $_POST['vehicletype'] ?? '';
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';
$errors = [];
if (empty($fullname) || empty($email) || empty($phone) || empty($vehicleno) || empty($vehicletype) || empty($username) || empty($password)) {
    echo json_encode([
        "success" => false,
        "message" => "Please fill in all required fields"
    ]);
    exit;
}
function existsInDb($conn, $field, $value) {
    $stmt = $conn->prepare("SELECT 1 FROM user WHERE $field = ?");
    $stmt->bind_param("s", $value);
    $stmt->execute();
    $stmt->store_result();
    $exists = $stmt->num_rows > 0;
    $stmt->close();
    return $exists;
}

if (existsInDb($conn, "Email", $email)) {
    $errors['email'] = "Email already exists";
}
if (existsInDb($conn, "Phone", $phone)) {
    $errors['phone'] = "Phone number already exists";
}
if (existsInDb($conn, "UserName", $username)) {
    $errors['username'] = "Username already exists";
}
if (existsInDb($conn, "VehicleNo", $vehicleno)) {
    $errors['vehicleno'] = "Vehicle number already exists";
}

if (!empty($errors)) {
    echo json_encode([
        "success" => false,
        "errors" => $errors
    ]);
    exit;
}

$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare("INSERT INTO user (Name, Email, Phone, VehicleNo, VehicleType, UserName, PassWord, Status,BookingStatus) VALUES (?, ?, ?, ?, ?, ?, ?, 'inactive','notbooked')");
$stmt->bind_param("sssssss", $fullname, $email, $phone, $vehicleno, $vehicletype, $username, $hashed_password);

if ($stmt->execute()) {
    echo json_encode([
        "success" => true,
        "message" => "Registration successful. Please verify your account."
    ]);
} else {
    echo json_encode([
        "success" => false,
        "message" => "Registration failed. Please try again."
    ]);
}

$stmt->close();
$conn->close();
?>
