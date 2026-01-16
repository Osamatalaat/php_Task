<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../config/jwt.php';


class AuthController {
    private $user;

    public function __construct() {
        $this->user = new User();
    }

    // Register
    public function register() {
        $data = json_decode(file_get_contents("php://input"), true);

        if(empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'All fields are required']);
            return;
        }

        // Check if user exists
        if($this->user->getByEmail($data['email'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email already exists']);
            return;
        }

        // Hash password
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $id = $this->user->create($data);

        http_response_code(201);
        echo json_encode([
            'message' => 'User registered successfully',
            'user_id' => $id
        ]);
    }

    // Login
    public function login() {
        $data = json_decode(file_get_contents("php://input"), true);

        if(empty($data['email']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Email and password are required']);
            return;
        }

        $user = $this->user->getByEmail($data['email']);

        if(!$user || !password_verify($data['password'], $user['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Invalid credentials']);
            return;
        }

        // Generate JWT
        $payload = [
            'user_id' => $user['id'],
            'email' => $user['email'],
            'iat' => time(),
            'exp' => time() + 3600 // 1 hour expiry
        ];

        $token = JWTConfig::encode($payload);

        http_response_code(200);
        echo json_encode(['token' => $token]);
    }
}
