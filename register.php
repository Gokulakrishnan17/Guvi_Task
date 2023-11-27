<?php
$servername = "localhost";
$username = "root";
$password = "GokulB@392002!";
$dbname = "users";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $location = $_POST['location'];

    // Upload profile photo
    $profilePhoto = $_FILES['profile_photo']['name'];
    $targetDirectory = "uploads/"; // Specify your upload directory
    $targetFile = $targetDirectory . basename($profilePhoto);
    move_uploaded_file($_FILES['profile_photo']['tmp_name'], $targetFile);

    $stmt = $conn->prepare("INSERT INTO user_details (name, username, email, phone, dob, password, location, profile_photo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssss", $name, $username, $email, $phone, $dob, $password, $location, $targetFile);
    
    if ($stmt->execute()) {
        $response = array("status" => "success", "message" => "User registered successfully");
    } else {
        $response = array("status" => "error", "message" => "Error registering user");
    }

    $stmt->close();
} else {
    $response = array("status" => "error", "message" => "Invalid request method");
}

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
