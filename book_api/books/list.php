<?php
include_once '../config/database.php';
require '../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$secret_key = "super_secret_key";
$headers = getallheaders();

if (isset($headers['Authorization'])) {
    $token = trim(str_replace("Bearer", "", $headers['Authorization']));

    try {
        $decoded = JWT::decode($token, new Key($secret_key, 'HS256'));

        // Token is valid, proceed to fetch books
        $query = "SELECT * FROM books ORDER BY created_at DESC";
        $stmt = $pdo->prepare($query);
        $stmt->execute();

        $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($books)) {
            echo json_encode(["books" => $books]);
        } else {
            echo json_encode(["message" => "No books found."]);
        }

    } catch (Exception $e) {
        http_response_code(401);
        echo json_encode(["message" => "Invalid token: " . $e->getMessage()]);
        exit;
    }
} else {
    http_response_code(401);
    echo json_encode(["message" => "Authorization token is missing."]);
    exit;
}
?>
