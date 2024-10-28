<?php
    require_once('./connection.php');
    $id = $_GET["id"];
    if (isset($_POST["action"]) && $_POST['action'] == 'Save') {
        $stmt = $pdo->prepare('UPDATE books SET title = :title, price = :price WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'title' => $_POST['title'],
            'price' => $_POST['price']
        ]);
        header("Location: ./book.php?id=$id");
        exit;
    }
    $stmt = $pdo->prepare('SELECT * FROM books WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $book = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit <?= htmlspecialchars($book['title']); ?></title>
    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }
        /* Body Styling */
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            background-color: #F4F4F9;
            color: #333;
        }
        /* Container for Book Details */
        .book-container {
            width: 90%;
            max-width: 600px;
            padding: 20px;
            background-color: #FFFFFF;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        /* Book Title Styling */
        .title {
            font-size: 28px;
            margin-bottom: 20px;
            color: #222;
        }
    </style>
</head>
<body>
    <div class="book-container">
        <h1 class="title">Edit <?= htmlspecialchars($book['title']); ?></h1>
        <form action="/edit.php?id=<?= $id; ?>" method="post">
            <label for="title">Title:</label><br>
            <input type="text" name="title" value="<?= htmlspecialchars($book['title']); ?>"><br><br>
            <label for="price">Price:</label><br>
            <input type="text" name="price" value="<?= htmlspecialchars($book['price']); ?>"><br><br>
            <input type="submit" name="action" value="Save">
        </form>
    </div>
</body>
</html>