<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthMiddleware
{
    public static function handle($data, $headers)
    {
        $authHeader = $headers['Authorization'] ?? '';

        if (!preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            http_response_code(401);
            echo json_encode(['error' => 'Token not provided']);
            exit;
        }

        $jwt = $matches[1];
        $config = require __DIR__ . './../../config/auth.php';


        try {
            $decoded = JWT::decode($jwt, new Key($config['jwt_secret'], 'HS256'));
            // Add user info to the request or session if needed
            $request['user'] = (array) $decoded;

            // Optionally, you can set the user ID in a session or request variable
            // $_SESSION['user_id'] = $decoded->user_id; // Example if using sessions
            // or $request['user_id'] = $decoded->user_id; // Example if using request array
            // Continue to the next middleware or controller

            return $request;
        } catch (Exception $e) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid or expired token']);
            exit;
        }
    }
}
