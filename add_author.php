<?php
require_once('./connection.php');

// Check if POST request is made
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $bookId = $_POST['book_id'];

    // Case 1: Adding an existing author
    if (isset($_POST['action']) && $_POST['action'] === 'add_author') {
        if (!empty($_POST['author_id'])) {
            try {
                // Insert the existing author into the book_authors table
                $stmt = $pdo->prepare('INSERT INTO book_authors (book_id, author_id) VALUES (:book_id, :author_id)');
                $stmt->execute([
                    'book_id' => $bookId, 
                    'author_id' => $_POST['author_id']
                ]);
            } catch (PDOException $e) {
                // Handle any database errors
                die('Error adding existing author: ' . $e->getMessage());
            }
        }
    }
    
    // Case 2: Adding a new author
    elseif (isset($_POST['action']) && $_POST['action'] === 'add_new_author') {
        if (!empty($_POST['first_name']) && !empty($_POST['last_name'])) {
            try {
                // Begin transaction
                $pdo->beginTransaction();
                
                // Insert the new author into the authors table
                $insertAuthorStmt = $pdo->prepare('INSERT INTO authors (first_name, last_name) VALUES (:first_name, :last_name)');
                $insertAuthorStmt->execute([
                    'first_name' => $_POST['first_name'],
                    'last_name' => $_POST['last_name']
                ]);

                // Get the ID of the newly added author
                $newAuthorId = $pdo->lastInsertId();

                // Associate the new author with the book
                $stmt = $pdo->prepare('INSERT INTO book_authors (book_id, author_id) VALUES (:book_id, :author_id)');
                $stmt->execute([
                    'book_id' => $bookId,
                    'author_id' => $newAuthorId
                ]);

                // Commit the transaction
                $pdo->commit();
                
            } catch (PDOException $e) {
                // Rollback the transaction on error
                $pdo->rollBack();
                die('Error adding new author: ' . $e->getMessage());
            }
        }
    }

    // Redirect back to the edit page
    header("Location: ./edit.php?id=" . $bookId);
    exit();
} 

// Redirect to index if not a POST request
header("Location: ./index.php");
exit();