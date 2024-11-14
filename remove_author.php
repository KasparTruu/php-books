<?php
require_once('./connection.php');

// Check if the ID is passed via GET parameter
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepare SQL statement to remove the author from the book
    $stmt = $pdo->prepare('UPDATE books SET author = NULL WHERE id = :id');
    $stmt->execute(['id' => $id]);

    // Redirect to the book details page or book list after removal
    header('Location: book.php?id=' . $id); // Redirect back to the book's detail page
    exit(); // Ensure no further code is executed after redirect
} else {
    // If ID is not provided, redirect back to the book list or display an error
    header('Location: index.php'); // Redirect to the book list page
    exit();
}
?>
