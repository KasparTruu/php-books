<?php
// Connect to the database
require_once('./connection.php');

// Retrieve the list of unique authors from the books table
$stmt = $pdo->query('SELECT DISTINCT author FROM books ORDER BY author ASC');
$authors = $stmt->fetchAll();

// Handle add author form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['new_author'])) {
    $new_author = $_POST['new_author'];
    if (!empty($new_author)) {
        $insert_stmt = $pdo->prepare('INSERT INTO books (author) VALUES (:author)');
        $insert_stmt->execute(['author' => $new_author]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle rename book form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['rename_book'])) {
    $book_id = $_POST['book_id'];
    $new_name = $_POST['new_name'];
    if (!empty($new_name)) {
        $update_stmt = $pdo->prepare('UPDATE books SET title = :new_name WHERE id = :book_id');
        $update_stmt->execute(['new_name' => $new_name, 'book_id' => $book_id]);
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Handle delete book form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_book'])) {
    $book_id = $_POST['book_id'];
    $delete_stmt = $pdo->prepare('DELETE FROM books WHERE id = :book_id');
    $delete_stmt->execute(['book_id' => $book_id]);
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Authors</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 90%;
            text-align: center;
        }
        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        .author-list {
            list-style-type: none;
            padding: 0;
        }
        .author-item {
            padding: 10px;
            font-size: 18px;
            color: #555;
        }
        .author-item:not(:last-child) {
            border-bottom: 1px solid #eee;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group input {
            padding: 8px;
            font-size: 16px;
            width: 80%;
        }
        .form-group button {
            padding: 10px 15px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .form-group button:hover {
            background-color: #45a049;
        }
        .btn-delete {
            background-color: #f44336;
        }
        .btn-delete:hover {
            background-color: #e53935;
        }
    </style>
</head>
<body>

<div class="container">
    <h1>Book Authors</h1>

    <!-- Add Author Form -->
    <form method="POST" class="form-group">
        <input type="text" name="new_author" placeholder="Add new author" required>
        <button type="submit">Add Author</button>
    </form>

    <ul class="author-list">
        <?php foreach ($authors as $author) { ?>
            <li class="author-item"><?= htmlspecialchars($author['author']); ?>
                <!-- Rename and Delete buttons for each book -->
                <form method="POST" style="display:inline;">
                    <input type="text" name="new_name" placeholder="Rename book" required>
                    <input type="hidden" name="book_id" value="<?= $author['id']; ?>">
                    <button type="submit" name="rename_book">Rename</button>
                </form>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="book_id" value="<?= $author['id']; ?>">
                    <button type="submit" name="delete_book" class="btn-delete">Delete</button>
                </form>
            </li>
        <?php } ?>
    </ul>
</div>

</body>
</html>
