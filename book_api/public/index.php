<?php
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = rtrim($uri, '/');

// Routes for Books
if(str_starts_with($uri, '/books')) {
    require_once "../routes/books.php";

// Routes for Auth
} elseif($uri === '/register' || $uri === '/login') {
    require_once "../routes/auth.php";

} else {
    http_response_code(404);
    echo json_encode(['error'=>'Endpoint not found']);
}
