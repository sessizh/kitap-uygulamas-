<?php
include_once '../config/database.php';

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    $query = "INSERT INTO users (email, password) VALUES (:email, :password)";
    $stmt = $pdo->prepare($query);
    $password_hash = password_hash($data->password, PASSWORD_BCRYPT);

    if ($stmt->execute(['email' => $data->email, 'password' => $password_hash])) {
        echo json_encode(["message" => "Registration successful."]);
    } else {
        echo json_encode(["message" => "Registration failed."]);
    }
} else {
    echo json_encode(["message" => "Please fill all fields."]);
}
?>
