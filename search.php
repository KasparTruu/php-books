<?php
require_once('./connection.php');

$searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

$stmt = $pdo->prepare('SELECT * FROM books WHERE title LIKE :title ORDER BY title ASC');
$stmt->execute(['title' => '%' . $searchTerm . '%']);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($books);
