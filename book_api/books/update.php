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

        // Token valid, proceed to update book
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->id) && !empty($data->title)) {
            $query = "UPDATE books SET 
                        title = :title, 
                        author = :author, 
                        category = :category, 
                        description = :description 
                      WHERE id = :id";

            $stmt = $pdo->prepare($query);

            $params = [
                'id' => $data->id,
                'title' => $data->title,
                'author' => $data->author ?? null,
                'category' => $data->category ?? null,
                'description' => $data->description ?? null
            ];

            if ($stmt->execute($params)) {
                echo json_encode(["message" => "Book updated successfully."]);
            } else {
                echo json_encode(["message" => "Failed to update book."]);
            }
        } else {
            echo json_encode(["message" => "Book ID and title are required."]);
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
