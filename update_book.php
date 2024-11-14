<?php
require_once('./connection.php');

$id = $_GET['id'];

// Retrieve the book details
$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

// Check if the form has been submitted for updating book details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title'], $_POST['price'])) {
        $newTitle = trim($_POST['title']);
        $newPrice = trim($_POST['price']);

        // Update the book title and price in the database
        $updateStmt = $pdo->prepare('UPDATE books SET title = :title, price = :price WHERE id = :id');
        $updateStmt->execute(['title' => $newTitle, 'price' => $newPrice, 'id' => $id]);

        // Refresh the book details after updating
        $stmt->execute(['id' => $id]);
        $book = $stmt->fetch();
        echo "Book details updated successfully!";
    } elseif (isset($_POST['new_author'])) {
        // Add a new author
        $newAuthor = trim($_POST['new_author']);
        
        if (!empty($newAuthor)) {
            try {
                // Update the author in the database
                $updateAuthorStmt = $pdo->prepare('UPDATE books SET author = :author WHERE id = :id');
                $updateAuthorStmt->execute(['author' => $newAuthor, 'id' => $id]);
                echo "Author updated successfully!";
            } catch (Exception $e) {
                echo "Error updating author: " . $e->getMessage();
            }
        } else {
            echo "Author name cannot be empty.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Book - <?= htmlspecialchars($book['title']) ?></title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f9; margin: 0; padding: 0; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .container { background-color: #ffffff; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); max-width: 600px; padding: 20px; text-align: center; }
        h1 { color: #333; }
        .form input { padding: 10px; width: 80%; margin-bottom: 10px; }
        .form button { padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
        .form button:hover { background-color: #0056b3; }
        .form .back-link { margin-top: 20px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Update Book Details: <?= htmlspecialchars($book['title']) ?></h1>

        <!-- Update Book Form -->
        <form method="POST">
            <input type="text" name="title" placeholder="New Title" value="<?= htmlspecialchars($book['title']) ?>" required>
            <input type="text" name="price" placeholder="New Price" value="<?= htmlspecialchars($book['price']) ?>" required>
            <button type="submit">Update Book</button>
        </form>

        <!-- Add Author Form -->
        <form method="POST">
            <input type="text" name="new_author" placeholder="New Author" required>
            <button type="submit">Add Author</button>
        </form>

        <a href="book.php?id=<?= $book['id'] ?>" class="back-link">Back to Book Details</a>
    </div>
</body>
</html>
