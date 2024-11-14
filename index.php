<?php
require_once('./connection.php');

// Initialize messages
$message = '';
$searchTerm = '';

// Check if a form has been submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_title'])) {
        // Add a book
        $addTitle = trim($_POST['add_title']);
        $addPrice = trim($_POST['add_price']);

        // Prepare and execute the insert statement
        $insertStmt = $pdo->prepare('INSERT INTO books (title, price) VALUES (:title, :price)');
        $insertStmt->execute(['title' => $addTitle, 'price' => $addPrice]);

        $message = 'Book added successfully.';
    } elseif (isset($_POST['remove_title'])) {
        // Remove a book
        $removeTitle = trim($_POST['remove_title']);

        // Prepare and execute the delete statement
        $deleteStmt = $pdo->prepare('DELETE FROM books WHERE title = :title');
        $deleteStmt->execute(['title' => $removeTitle]);

        $message = 'Book removed successfully.';
    } elseif (isset($_POST['search_term'])) {
        // Search for a book
        $searchTerm = trim($_POST['search_term']);
    }
}

// Fetch books based on search term
if ($searchTerm) {
    $stmt = $pdo->prepare('SELECT * FROM books WHERE title LIKE :title ORDER BY title ASC');
    $stmt->execute(['title' => '%' . $searchTerm . '%']);
} else {
    $stmt = $pdo->query('SELECT * FROM books ORDER BY title ASC');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bookstore</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            position: relative;
        }
        .bookstore-container {
            width: 100%;
            max-width: 600px;
            padding: 20px;
            text-align: center;
        }
        h1 {
            color: #333;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        .book-list {
            padding: 0;
            list-style-type: none;
            margin: 0;
            margin-bottom: 20px;
        }
        .book-item {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            margin: 10px 0;
            text-align: center;
            text-decoration: none;
            color: #333;
            transition: transform 0.2s, box-shadow 0.2s;
            display: block;
        }
        .book-item:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }
        .form-container, .search-container {
            margin-top: 20px;
        }
        .form-container input, .search-container input {
            padding: 10px;
            width: 70%;
            margin-bottom: 10px;
        }
        .form-container button, .search-container button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .form-container button:hover, .search-container button:hover {
            background-color: #0056b3;
        }
        .message {
            color: green;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="bookstore-container">
    <h1>Welcome to the Bookstore</h1>

    <!-- Search Bar -->
    <div class="search-container">
        <form method="POST">
            <input type="text" name="search_term" placeholder="Search by book title" value="<?= htmlspecialchars($searchTerm); ?>">
            <button type="submit">Search</button>
        </form>
    </div>

    <ul class="book-list">
        <?php while ($row = $stmt->fetch()) { ?>
            <li>
                <a href="./book.php?id=<?= htmlspecialchars($row['id']); ?>" class="book-item">
                    <div class="book-title"><?= htmlspecialchars($row['title']); ?></div>
                    <div class="book-price">Price: $<?= htmlspecialchars(number_format($row['price'], 2)); ?></div>
                </a>
            </li>
        <?php } ?>
    </ul>

    <?php if ($message) { ?>
        <div class="message"><?= htmlspecialchars($message); ?></div>
    <?php } ?>

    <div class="form-container">
        <h2>Add a Book</h2>
        <form method="POST">
            <input type="text" name="add_title" placeholder="Enter book title to add" required>
            <input type="number" name="add_price" placeholder="Enter book price" step="0.01" required>
            <button type="submit">Add Book</button>
        </form>
        
        <h2>Remove a Book</h2>
        <form method="POST">
            <input type="text" name="remove_title" placeholder="Enter book title to remove" required>
            <button type="submit">Remove Book</button>
        </form>
    </div>
</div>

</body>
</html>
