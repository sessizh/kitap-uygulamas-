<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: DELETE");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

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
    $decoded = JWT::decode($jwt, new Key($secret_key, 'HS256'));
    if ($decoded->data->role !== 'admin') {
        http_response_code(403);
        echo json_encode(["message" => "Only admins can delete books."]);
        exit;
    }
} catch (Exception $e) {
    http_response_code(401);
    echo json_encode(["message" => "Invalid token."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"));
if (!empty($data->id)) {
    $query = "DELETE FROM books WHERE id = :id";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute(['id' => $data->id])) {
        echo json_encode(["message" => "Book deleted successfully."]);
    } else {
        echo json_encode(["message" => "Failed to delete book."]);
    }
} else {
    echo json_encode(["message" => "Book ID is required."]);
}
?>
