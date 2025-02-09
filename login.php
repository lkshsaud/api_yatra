<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Content-Type");
header("Access-Control-Allow-Methods: POST");

include 'db_config.php';

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['email']) && isset($data['password'])) {
    $email = $data['email'];
    $password = $data['password'];

    
    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user) {
        
        if (password_verify($password, $user['password'])) {
            echo json_encode(["success" => true, "message" => "Login successful!", "username" => $user['username']]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid password!"]);
        }
    } else {
        echo json_encode(["success" => false, "message" => "User not found!"]);
    }

    $stmt->close();
} else {
    echo json_encode(["success" => false, "message" => "Missing email or password"]);
}

$conn->close();
?>
