<?php

require_once('./connection.php');

$id = $_GET['id'];

// get book data
$stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
$stmt->execute(['id' => $id]);
$book = $stmt->fetch();

// get book authors
$bookAuthorsStmt = $pdo->prepare('SELECT * FROM book_authors ba LEFT JOIN authors a ON ba.author_id=a.id WHERE ba.book_id = :id');
$bookAuthorsStmt->execute(['id' => $id]);

// get available authors
$availableAuthorsStmt = $pdo->prepare('SELECT * FROM authors WHERE id NOT IN (SELECT author_id FROM book_authors WHERE book_id = :book_id)');
$availableAuthorsStmt->execute(['book_id' => $id]);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Book</title>
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

        h3 {
            font-size: 24px;
            color: #333;
            margin-top: 20px;
        }

        form {
            background-color: white;
            padding: 20px;
            margin: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        label {
            font-weight: bold;
            margin-bottom: 10px;
            display: block;
        }

        input[type="text"], select {
            width: 100%;
            padding: 8px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        button {
            background-color: #007bff; /* Blue button */
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }

        ul {
            list-style-type: none;
            padding-left: 0;
        }

        ul li {
            margin: 10px 0;
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }

        .remove-btn {
            cursor: pointer;
            background-color: transparent;
            border: none;
            color: #d9534f;
        }

        .remove-btn:hover {
            color: #c9302c;
        }

        svg {
            vertical-align: middle;
            margin-left: 8px;
        }

        /* Custom Blue Styled Inputs */
        input[type="text"]:focus, select:focus {
            border-color: #007bff; /* Blue border on focus */
            outline: none;
        }
    </style>
</head>
<body>

    <nav>
        <a href="./book.php?id=<?= $id; ?>">Back</a>
    </nav>
    
    <h3><?= $book['title'];?></h3>

    <form action="./update_book.php?id=<?= $id; ?>" method="post">
        <label for="title">Title:</label>
        <input type="text" name="title" value="<?= $book['title'];?>">
        
        <label for="price">Price:</label>
        <input type="text" name="price" value="<?= $book['price'];?>">
        
        <input type="submit" name="action" value="Save">
    </form>

    <h3>Authors:</h3>

    <ul>
        <?php while ( $author = $bookAuthorsStmt->fetch() ) { ?>
            <li>
                <?= $author['first_name']; ?> <?= $author['last_name']; ?>
                <form action="./remove_author.php?id=<?= $id; ?>" method="post" style="display:inline;">
                    <button type="submit" name="action" value="remove_author" class="remove-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 24 24">
                            <path d="M 10.806641 2 C 10.289641 2 9.7956875 2.2043125 9.4296875 2.5703125 L 9 3 L 4 3 A 1.0001 1.0001 0 1 0 4 5 L 20 5 A 1.0001 1.0001 0 1 0 20 3 L 15 3 L 14.570312 2.5703125 C 14.205312 2.2043125 13.710359 2 13.193359 2 L 10.806641 2 z M 4.3652344 7 L 5.8925781 20.263672 C 6.0245781 21.253672 6.877 22 7.875 22 L 16.123047 22 C 17.121047 22 17.974422 21.254859 18.107422 20.255859 L 19.634766 7 L 4.3652344 7 z"></path>
                        </svg>
                    </button>
                    <input type="hidden" name="author_id" value="<?= $author['id']; ?>">
                </form>
            </li>
        <?php } ?>
    </ul>

    <form action="./add_author.php" method="post">
        <input type="hidden" name="book_id" value="<?= $id; ?>">
        <h4>Add an existing Author</h4>
        <select name="author_id">
            <option value=""></option>
            <?php while ( $author = $availableAuthorsStmt->fetch() ) { ?>
                <option value="<?= $author['id']; ?>">
                    <?= $author['first_name']; ?> <?= $author['last_name']; ?>
                </option>
            <?php } ?>
        </select>

        <button type="submit" name="action" value="add_author">Add author</button>
    </form>

    <form action="./add_author.php" method="post">
        <input type="hidden" name="book_id" value="<?= $id; ?>">
        <h4>Add a New Author</h4>

        <label for="first_name">First Name:</label>
        <input type="text" name="first_name" required>

        <label for="last_name">Last Name:</label>
        <input type="text" name="last_name" required>

        <button type="submit" name="action" value="add_new_author">Add New Author</button>
    </form>

</body>
</html>
