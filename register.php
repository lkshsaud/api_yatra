<?php
include 'db_config.php';

// Read JSON input
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Check if required fields are provided and not empty
if (!empty($data['username']) && !empty($data['email']) && !empty($data['password'])) {
    $username = trim($data['username']);
    $email = trim($data['email']);
    $password = password_hash($data['password'], PASSWORD_DEFAULT); // Hash password securely

    // Check if username or email already exists
    $query = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo json_encode(["success" => false, "message" => "Username or Email already exists!"]);
    } else {
        // Insert user into the database
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("sss", $username, $email, $password);

        if ($stmt->execute()) {
            echo json_encode(["success" => true, "message" => "User registered successfully!"]);
        } else {
            echo json_encode(["success" => false, "message" => "Registration failed!"]);
        }
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "All fields are required!"]);
}

$conn->close();
?>
