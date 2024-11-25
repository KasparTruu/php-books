<?php
require_once('./connection.php');

// Check if there is a search query
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the SQL query to include a search condition
$stmt = $pdo->prepare('SELECT * FROM books WHERE is_deleted = 0 AND title LIKE :search');
$stmt->execute(['search' => '%' . $search . '%']);
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
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        .page-title {
            font-size: 36px;
            color: #007bff;
            text-align: center;
            margin-top: 30px;
        }

        .search-bar {
            text-align: center;
            margin: 20px 0;
        }

        .search-bar input[type="text"] {
            padding: 10px;
            width: 300px;
            font-size: 16px;
            border: 2px solid #007bff;
            border-radius: 4px;
        }

        .search-bar button {
            padding: 10px 20px;
            font-size: 16px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-bar button:hover {
            background-color: #0056b3;
        }

        .book-list {
            list-style-type: none;
            padding-left: 0;
            margin-top: 20px;
            text-align: center;
        }

        .book-list li {
            font-size: 18px;
            margin: 10px 0;
            padding: 5px;
            border-bottom: 1px solid #ddd;
        }

        .book-list a {
            text-decoration: none;
            color: #007bff;
            font-size: 20px;
        }

        .book-list a:hover {
            color: #0056b3;
        }

        .no-books {
            font-size: 18px;
            color: #333;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<h1 class="page-title">Welcome to my Book Store!</h1>

<!-- Search Form -->
<div class="search-bar">
    <form method="GET" action="">
        <input type="text" name="search" placeholder="Search for a book..." value="<?= htmlspecialchars($search); ?>">
        <button type="submit">Search</button>
    </form>
</div>

<ul class="book-list">
    <?php while ($row = $stmt->fetch()) { ?>
        <li>
            <a href="./book.php?id=<?= $row['id']; ?>" class="book-section">
                <?= htmlspecialchars($row['title']); ?>
            </a>
        </li>
    <?php } ?>

    <?php if ($stmt->rowCount() === 0) { ?>
        <li class="no-books">No books found.</li>
    <?php } ?>
</ul>

</body>
</html>
