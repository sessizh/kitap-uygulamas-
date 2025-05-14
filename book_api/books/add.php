<?php
header("Content-Type: application/json");
include_once '../config/database.php';
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "super_secret_key";
$headers = apache_request_headers();

if (!isset($headers['Authorization'])) {
    http_response_code(401);
    echo json_encode(["message" => "Token required."]);
    exit;
}

list(, $jwt) = explode(" ", $headers['Authorization']);
try {
    JWT::decode($jwt, new Key($secret_key, 'HS256'));
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid token."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"));

if (!empty($data->title) && !empty($data->author) && !empty($data->category)) {
    $query = "INSERT INTO books (title, author, category, description) VALUES (:title, :author, :category, :description)";
    $stmt = $pdo->prepare($query);

    if ($stmt->execute([
        'title' => $data->title,
        'author' => $data->author,
        'category' => $data->category,
        'description' => $data->description
    ])) {
        echo json_encode(["message" => "Book added successfully."]);
    } else {
        echo json_encode(["message" => "Failed to add book."]);
    }
} else {
    echo json_encode(["message" => "All fields are required."]);
}
?>
