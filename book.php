<?php

require_once('./connection.php');

$id = $_GET['id'];

$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

$stmt = $pdo->prepare('SELECT * FROM book_authors ba LEFT JOIN authors a ON ba.author_id=a.id WHERE ba.book_id = :id');
$stmt->execute(['id' => $id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
            color: #333;
        }

        nav {
            background-color: #007bff; /* Blue background */
            padding: 10px;
            text-align: center;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-size: 16px;
        }

        h1 {
            font-size: 32px;
            color: #333;
            text-align: center;
            margin-top: 20px;
        }

        h2 {
            font-size: 24px;
            color: #333;
            margin-top: 20px;
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        ul li {
            font-size: 18px;
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        img {
            display: block;
            margin: 20px auto;
            max-width: 300px;
            border-radius: 8px;
        }

        p {
            font-size: 20px;
            text-align: center;
            color: #333;
        }

        a {
            display: block;
            text-align: center;
            font-size: 18px;
            color: #007bff;
            text-decoration: none;
            margin-top: 20px;
        }

        a:hover {
            color: #0056b3;
        }

        form {
            text-align: center;
            margin-top: 30px;
        }

        form input[type="submit"] {
            background-color: #007bff; /* Blue button */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            font-size: 18px;
            cursor: pointer;
        }

        form input[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
    </style>
</head>
<body>
    <nav>
        <a href="/index.php">Back</a>
    </nav>

    <h1><?= $book['title'];?></h1>
    
    <?php if ($book['cover_path']) { ?>
        <img src="<?= $book['cover_path']; ?>" alt="Cover of <?= htmlspecialchars($book['title']); ?>">
    <?php } ?>

    <h2>Authors:</h2>
    <ul>
        <?php while ( $author = $stmt->fetch() ) { ?>
            <li>
                <?= $author['first_name']; ?> <?= $author['last_name']; ?>
            </li>
        <?php } ?>
    </ul>

    <p>Price: <?= round($book['price'], 2); ?> &euro;</p>

    <a href="./edit.php?id=<?= $id; ?>">Edit</a>

    <br><br>
    <form action="./delete.php" method="post">
        <input type="hidden" name="id" value="<?= $id; ?>">
        <input type="submit" name="action" value="Delete">
    </form>
</body>
</html>
