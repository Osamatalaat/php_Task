<?php

require_once "../models/Book.php";

class BookController {

    private $book;

    public function __construct() {
        $this->book = new Book();
    }

    // GET /books/GetAll
  public function index() {
    if (isset($_GET['search'])) {
        $books = $this->book->search($_GET['search']);
    } else {
        $books = $this->book->getAll();
    }
    http_response_code(200);
    echo json_encode($books);
}


    // GET /books/Get/{id}
    public function show($id) {
        $book = $this->book->getById($id);

        if (!$book) {
            http_response_code(404);
            echo json_encode(["error" => "Book not found"]);
            return;
        }

        http_response_code(200);
        echo json_encode($book);
    }

    // POST /books/Create
public function store() {
    $data = json_decode(file_get_contents("php://input"), true);

    // Validation
    if (empty($data['title']) || empty($data['author']) || empty($data['year'])) {
        http_response_code(400);
        echo json_encode(["error" => "Title, author, and year are required"]);
        return;
    }

    if ($data['year'] < 1000 || $data['year'] > 9999) {
        http_response_code(400);
        echo json_encode(["error" => "Year must be between 1000 and 9999"]);
        return;
    }

    $book = $this->book->create($data);
    http_response_code(201);
    echo json_encode($book);
}

// PUT /books/Update/{id}
public function updateBook($id) {
    $data = json_decode(file_get_contents("php://input"), true);

    $book = $this->book->getById($id);
    if (!$book) {
        http_response_code(404);
        echo json_encode(["error" => "Book not found"]);
        return;
    }

    $updatedBook = $this->book->update($id, $data);
    http_response_code(200);
    echo json_encode($updatedBook);
}

// DELETE /books/Delete/{id}
public function destroy($id) {
    $book = $this->book->getById($id);
    if (!$book) {
        http_response_code(404);
        echo json_encode(["error" => "Book not found"]);
        return;
    }

    $this->book->delete($id);
    http_response_code(200);
    echo json_encode(["message" => "Book deleted successfully"]);
}

// POST /books/borrow/{id}
public function borrow($id) {
    $book = $this->book->getById($id);
    if (!$book) {
        http_response_code(404);
        echo json_encode(["error" => "Book not found"]);
        return;
    }
    $book = $this->book->borrow($id);
    http_response_code(200);
    echo json_encode($book);
}

// POST /books/return/{id}
public function return($id) {
    $book = $this->book->getById($id);
    if (!$book) {
        http_response_code(404);
        echo json_encode(["error" => "Book not found"]);
        return;
    }
    $book = $this->book->return($id);
    http_response_code(200);
    echo json_encode($book);
}

}
