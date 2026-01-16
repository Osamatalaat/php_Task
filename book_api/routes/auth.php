<?php
require_once "../controllers/AuthController.php";
$controller = new AuthController();

// Register
if($method === 'POST' && $uri === '/register') {
    $controller->register();
    exit;
}

// Login
if($method === 'POST' && $uri === '/login') {
    $controller->login();
    exit;
}

// Not Found
http_response_code(404);
echo json_encode(['error' => 'Endpoint not found']);
