

<?php

require_once "../config/database.php";

class Book {

    private $conn;
    private $table = "books";

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id) {
        $stmt = $this->conn->prepare(
            "SELECT * FROM {$this->table} WHERE id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Create a new book
public function create($data) {
    $stmt = $this->conn->prepare(
        "INSERT INTO {$this->table} (title, author, year, genre, is_borrowed) 
         VALUES (:title, :author, :year, :genre, :is_borrowed)"
    );
    $stmt->execute([
        'title' => $data['title'],
        'author' => $data['author'],
        'year' => $data['year'],
        'genre' => $data['genre'] ?? null,
        'is_borrowed' => $data['is_borrowed'] ?? false
    ]);
    return $this->getById($this->conn->lastInsertId());
}

// Update an existing book
public function update($id, $data) {
    $stmt = $this->conn->prepare(
        "UPDATE {$this->table} SET 
            title = :title, 
            author = :author, 
            year = :year, 
            genre = :genre, 
            is_borrowed = :is_borrowed
         WHERE id = :id"
    );
    $stmt->execute([
        'id' => $id,
        'title' => $data['title'],
        'author' => $data['author'],
        'year' => $data['year'],
        'genre' => $data['genre'] ?? null,
        'is_borrowed' => $data['is_borrowed'] ?? false
    ]);
    return $this->getById($id);
}

// Delete a book
public function delete($id) {
    $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
    return $stmt->execute(['id' => $id]);
}

// Borrow a book
public function borrow($id) {
    $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_borrowed = 1 WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $this->getById($id);
}

// Return a book
public function return($id) {
    $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_borrowed = 0 WHERE id = :id");
    $stmt->execute(['id' => $id]);
    return $this->getById($id);
}

public function search($keyword) {
    $stmt = $this->conn->prepare(
        "SELECT * FROM {$this->table} WHERE title LIKE :kw OR author LIKE :kw"
    );
    $stmt->execute(['kw' => "%$keyword%"]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


}
