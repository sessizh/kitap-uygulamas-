<?php
include_once '../config/database.php';
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "super_secret_key";
$data = json_decode(file_get_contents("php://input"));

if (!empty($data->email) && !empty($data->password)) {
    $query = "SELECT * FROM users WHERE email = :email LIMIT 1";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['email' => $data->email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($data->password, $user['password'])) {
        $payload = [
            "iss" => "localhost",
            "iat" => time(),
            "exp" => time() + 3600,
            "data" => [
                "id" => $user['id'],
                "email" => $user['email'],
                "role" => $user['role']
            ]
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');
        echo json_encode(["token" => $jwt]);
    } else {
        echo json_encode(["message" => "Invalid credentials."]);
    }
} else {
    echo json_encode(["message" => "Please fill all fields."]);
}
?>
