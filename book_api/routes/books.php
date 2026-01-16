<?php
require_once __DIR__ . '/../controllers/BookController.php';
require_once __DIR__ . '/../middleware/auth.php';

$controller = new BookController();
$decodedUser = authenticate(); // حماية كل الـ endpoints

// GET /books/GetAll
if ($_SERVER['REQUEST_METHOD'] === 'GET' && $uri === '/books/GetAll') {
    $controller->index();
    exit;
}

// GET /books/Get/{id}
if ($_SERVER['REQUEST_METHOD'] === 'GET' && preg_match('#^/books/Get/(\d+)$#', $uri, $matches)) {
    $controller->show($matches[1]);
    exit;
}

// POST /books/Create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $uri === '/books/Create') {
    $controller->store();
    exit;
}

// PUT /books/Update/{id}
if ($_SERVER['REQUEST_METHOD'] === 'PUT' && preg_match('#^/books/Update/(\d+)$#', $uri, $matches)) {
    $controller->updateBook($matches[1]);
    exit;
}

// DELETE /books/Delete/{id}
if ($_SERVER['REQUEST_METHOD'] === 'DELETE' && preg_match('#^/books/Delete/(\d+)$#', $uri, $matches)) {
    $controller->destroy($matches[1]);
    exit;
}

// POST /books/Borrow/{id}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && preg_match('#^/books/Borrow/(\d+)$#', $uri, $matches)) {
    $controller->borrow($matches[1]);
    exit;
}

// POST /books/Return/{id}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && preg_match('#^/books/Return/(\d+)$#', $uri, $matches)) {
    $controller->return($matches[1]);
    exit;
}

// Not Found
http_response_code(404);
echo json_encode(["error" => "Endpoint not found"]);
