<?php
require_once('./connection.php');

$id = $_GET['id'];

// Retrieve the book details
$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newTitle = trim($_POST['title']);
    $newPrice = trim($_POST['price']);

    // Update the book title and price in the database
    $updateStmt = $pdo->prepare('UPDATE books SET title = :title, price = :price WHERE id = :id');
    $updateStmt->execute(['title' => $newTitle, 'price' => $newPrice, 'id' => $id]);

    // Refresh the book details after updating
    $stmt->execute(['id' => $id]);
    $book = $stmt->fetch();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore - <?= htmlspecialchars($book['title']) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;  
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            max-width: 600px;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #333;
        }
        .author, .year, .description {
            font-size: 1rem;
            color: #666;
            margin: 10px 0;
        }
        .description {
            text-align: justify;
        }
        .back-link, .update-button {
            display: inline-block;
            margin-top: 20px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover, .update-button:hover {
            text-decoration: underline;
        }
        .form {
            margin-top: 20px;
        }
        .form input {
            padding: 10px;
            width: 80%;
            margin-bottom: 10px;
        }
        .form button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1><?= htmlspecialchars($book['title']) ?></h1>
        <p class="author">Author: <?= htmlspecialchars($book['author']) ?></p>
        <p class="year">Published: <?= htmlspecialchars($book['year_published']) ?></p>
        <p class="description"><?= htmlspecialchars($book['description']) ?></p>
        <p class="price">Price: $<?= htmlspecialchars(number_format($book['price'], 2)) ?></p>

        <div class="form">
            <h2>Change Book Details</h2>
            <form method="POST">
                <input type="text" name="title" placeholder="Enter new book title" required>
                <input type="number" name="price" placeholder="Enter new price" step="0.01" required>
                <button type="submit">Update Book</button>
            </form>
        </div>

        <a class="back-link" href="/">Back to Bookstore</a>
    </div>
</body>
</html>
