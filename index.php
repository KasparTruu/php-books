<?php
require_once('./connection.php');

// Fetch all books in alphabetical order by title
$stmt = $pdo->query('SELECT * FROM books ORDER BY title ASC');
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
        .book-title {
            font-size: 1.1rem;
            font-weight: bold;
        }
    </style>
</head>
<body>

<div class="bookstore-container">
    <h1>Welcome to the Bookstore</h1>
    <ul class="book-list">
        <?php while ($row = $stmt->fetch()) { ?>
            <li>
                <a href="./book.php?id=<?= htmlspecialchars($row['id']); ?>" class="book-item">
                    <div class="book-title"><?= htmlspecialchars($row['title']); ?></div>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>

</body>
</html>
