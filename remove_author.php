<?php

// remove author from book
if (isset($_POST['action']) && $_POST['action'] == 'remove_author') {

    require_once('./connection.php');

    $id = $_GET['id'];

    // Ensure the parameter name matches the one sent in the form
    $stmt = $pdo->prepare('DELETE FROM book_authors WHERE book_id = :book_id AND author_id = :author_id');
    $stmt->execute(['book_id' => $id, 'author_id' => $_POST['author_id']]);

    // Redirect back to the edit page for the book
    header("Location: ./edit.php?id={$id}");
    exit();
} else {
    // If action is not 'remove_author', redirect to the index page
    header("Location: ./index.php");
    exit();
}
