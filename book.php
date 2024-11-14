<?php
require_once('./connection.php');

$id = $_GET['id'];

// Retrieve the book details
$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore - <?= htmlspecialchars($book['title']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); max-width: 600px; padding: 20px; text-align: center; }
        h1 { color: #333; }
        .author, .year, .description, .price { font-size: 1rem; color: #666; margin: 10px 0; }
        .back-link { margin-top: 20px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($book['title']) ?></h1>
        <p class="author">Author: <?= htmlspecialchars($book['author']) ?: 'No author listed' ?></p>
        <p class="year">Published: <?= htmlspecialchars($book['year_published']) ?></p>
        <p class="description"><?= htmlspecialchars($book['description']) ?></p>
        <p class="price">Price: $<?= htmlspecialchars(number_format($book['price'], 2)) ?></p>

        <a href="update_book.php?id=<?= $book['id'] ?>" class="back-link">Update Book</a>
    </div>
</body>
</html>
