<?php

namespace Controllers;

use Database;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class UserController {
    private $db;
    private $users;
    private $config;
    private $secret;
    private $expiry;
    private $decode;

    public function __construct($decode = null) {
        $this->db = Database::connect();
        $this->users = $this->db->selectCollection("users");
        $this->config = require __DIR__ . "/../../config/auth.php";
	    $this->secret = $this->config['jwt_secret'];
	    $this->expiry = $this->config['jwt_expire'];
        $this->decode = $decode;
    }

    public function profile() {
        header('Content-Type: application/json');
        echo json_encode(['message' => 'profile endpoint is working, authentication successful']);
    }

    public function register($data) {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(["error" => "Email and password are required"]);
            return;
        }

        // Check if user already exists
        $existing = $this->users->findOne(['email' => $email]);
        if ($existing) {
            http_response_code(409);
            echo json_encode(["error" => "Email already registered"]);
            return;
        }

        // Insert new user
        $result = $this->users->insertOne([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT),
            'created_at' => date('c') // ISO8601 format, e.g., "2025-06-05T12:34:56+00:00"
        ]);

        echo json_encode(["message" => "User registered", "user_id" => (string)$result->getInsertedId()]);
    }

    public function login($data) {
        header('Content-Type: application/json');

        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;

        if (!$email || !$password) {
            http_response_code(400);
            echo json_encode(['error' => 'Email and password required']);
            return;
        }

        $user = $this->db->findOne(['email' => $email]);

        if (!$user || !password_verify($password, $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }

        $payload = [
            'sub' => (string)$user['_id'],
            'email' => $user['email'],
            'exp' => time() + $this->expiry // 1 day
        ];

        $jwt = JWT::encode($payload, $this->secret, 'HS256');

        echo json_encode([
            'token' => $jwt,
            'message' => 'Login successful'
        ]);
    }
}

